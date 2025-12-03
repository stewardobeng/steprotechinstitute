<?php

namespace App\Services;

use App\Models\TwoFactorAuthentication;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use PragmaRX\Google2FA\Google2FA;
use chillerlan\QRCode\QRCode as QRCodeGenerator;
use chillerlan\QRCode\QROptions;

class TwoFactorService
{
    private Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Generate a secret key for the user
     */
    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * Get QR code URL for the user
     */
    public function getQRCodeUrl(User $user, string $secret): string
    {
        $companyName = config('app.name', 'Laravel');
        $companyEmail = $user->email;
        
        return $this->google2fa->getQRCodeUrl(
            $companyName,
            $companyEmail,
            $secret
        );
    }

    /**
     * Get QR code as SVG markup string
     */
    public function getQRCodeSvg(User $user, string $secret): string
    {
        $qrCodeUrl = $this->getQRCodeUrl($user, $secret);
        
        $options = new QROptions([
            'outputType' => QRCodeGenerator::OUTPUT_MARKUP_SVG,
            'outputBase64' => false,  // Return raw SVG, not data URI
            'eccLevel'   => QRCodeGenerator::ECC_M,
            'scale'      => 10,
            'addQuietzone' => true,
            'markupDark' => '#000000',
            'markupLight' => '#ffffff',
        ]);
        
        $qrCode = new QRCodeGenerator($options);
        return $qrCode->render($qrCodeUrl);
    }

    /**
     * Enable 2FA for user
     */
    public function enableTwoFactor(User $user, string $verificationCode): array
    {
        try {
            $twoFactor = TwoFactorAuthentication::where('user_id', $user->id)->first();
            
            if (!$twoFactor || !$twoFactor->secret_key) {
                return [
                    'success' => false,
                    'message' => 'Please scan the QR code first to generate a secret key.',
                ];
            }

            // Verify the code before enabling (use verifySetupCode since 2FA is not enabled yet)
            if (!$this->verifySetupCode($user, $verificationCode)) {
                return [
                    'success' => false,
                    'message' => 'Invalid verification code. Please try again.',
                ];
            }

            // Generate backup codes
            $backupCodes = $this->generateBackupCodes();

            // Enable 2FA
            $twoFactor->update([
                'enabled' => true,
                'backup_codes' => $backupCodes,
            ]);

            // Update user
            $user->update(['two_factor_enabled' => true]);

            return [
                'success' => true,
                'backup_codes' => $backupCodes,
            ];
        } catch (\Exception $e) {
            Log::error('Failed to enable 2FA', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to enable two-factor authentication.',
            ];
        }
    }

    /**
     * Prepare 2FA setup (generate secret and QR code)
     */
    public function prepareSetup(User $user): array
    {
        try {
            $secret = $this->generateSecretKey();
            
            // Store or update the secret (but don't enable yet)
            TwoFactorAuthentication::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'secret_key' => $secret,
                    'enabled' => false,
                ]
            );

            $qrCodeUrl = $this->getQRCodeUrl($user, $secret);
            $qrCodeSvg = $this->getQRCodeSvg($user, $secret);

            return [
                'success' => true,
                'secret' => $secret,
                'qr_code_url' => $qrCodeUrl,
                'qr_code_svg' => $qrCodeSvg,
            ];
        } catch (\Exception $e) {
            Log::error('Failed to prepare 2FA setup', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to prepare two-factor authentication setup.',
            ];
        }
    }

    /**
     * Disable 2FA for user
     */
    public function disableTwoFactor(User $user): bool
    {
        try {
            TwoFactorAuthentication::where('user_id', $user->id)->delete();
            $user->update(['two_factor_enabled' => false]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to disable 2FA', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Check if 2FA is enabled for user
     */
    public function isEnabled(User $user): bool
    {
        $twoFactor = TwoFactorAuthentication::where('user_id', $user->id)
            ->where('enabled', true)
            ->first();

        return $twoFactor !== null;
    }

    /**
     * Verify 2FA code
     */
    public function verifyCode(User $user, string $code): bool
    {
        $twoFactor = TwoFactorAuthentication::where('user_id', $user->id)
            ->where('enabled', true)
            ->first();

        if (!$twoFactor || !$twoFactor->secret_key) {
            return false;
        }

        // Check backup codes first
        $backupCodes = $twoFactor->backup_codes ?? [];
        if (in_array($code, $backupCodes)) {
            // Remove used backup code
            $backupCodes = array_values(array_diff($backupCodes, [$code]));
            $twoFactor->update(['backup_codes' => $backupCodes]);
            return true;
        }

        // Verify TOTP code with time window of 2 to account for clock skew
        return $this->google2fa->verifyKey($twoFactor->secret_key, $code, 2);
    }

    /**
     * Verify code during setup (before enabling)
     */
    public function verifySetupCode(User $user, string $code): bool
    {
        $twoFactor = TwoFactorAuthentication::where('user_id', $user->id)->first();
        
        if (!$twoFactor || !$twoFactor->secret_key) {
            return false;
        }

        // Use a window of 2 to account for clock skew (allows codes from -1 to +1 time steps)
        return $this->google2fa->verifyKey($twoFactor->secret_key, $code, 2);
    }

    /**
     * Regenerate backup codes for user
     */
    public function regenerateBackupCodes(User $user): array
    {
        try {
            $twoFactor = TwoFactorAuthentication::where('user_id', $user->id)
                ->where('enabled', true)
                ->first();

            if (!$twoFactor) {
                return [
                    'success' => false,
                    'message' => 'Two-factor authentication is not enabled.',
                ];
            }

            $backupCodes = $this->generateBackupCodes();
            $twoFactor->update(['backup_codes' => $backupCodes]);

            return [
                'success' => true,
                'backup_codes' => $backupCodes,
            ];
        } catch (\Exception $e) {
            Log::error('Failed to regenerate backup codes', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to regenerate backup codes.',
            ];
        }
    }

    /**
     * Generate backup codes
     */
    private function generateBackupCodes(int $count = 10): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        }
        return $codes;
    }
}

