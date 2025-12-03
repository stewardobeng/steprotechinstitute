<?php

namespace App\Services;

use App\Models\TwoFactorAuthentication;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PassKitService
{
    private ?string $apiKey;
    private ?string $apiSecret;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('passkit.api_key');
        $this->apiSecret = config('passkit.api_secret');
        $this->baseUrl = config('passkit.base_url', 'https://api.passkit.io');
    }

    /**
     * Enable 2FA for user
     */
    public function enableTwoFactor(User $user): array
    {
        try {
            // Generate backup codes
            $backupCodes = $this->generateBackupCodes();

            // Create PassKit identifier (you'll need to implement actual PassKit API call)
            $passkitIdentifier = $this->createPassKitIdentifier($user);

            // Store in database
            $twoFactor = TwoFactorAuthentication::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'passkit_identifier' => $passkitIdentifier,
                    'enabled' => true,
                    'backup_codes' => $backupCodes,
                ]
            );

            // Update user
            $user->update(['two_factor_enabled' => true]);

            return [
                'success' => true,
                'backup_codes' => $backupCodes,
                'passkit_identifier' => $passkitIdentifier,
            ];
        } catch (\Exception $e) {
            Log::error('Failed to enable 2FA', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to enable two-factor authentication',
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

        if (!$twoFactor) {
            return false;
        }

        // Check backup codes
        $backupCodes = $twoFactor->backup_codes ?? [];
        if (in_array($code, $backupCodes)) {
            // Remove used backup code
            $backupCodes = array_values(array_diff($backupCodes, [$code]));
            $twoFactor->update(['backup_codes' => $backupCodes]);
            return true;
        }

        // Verify with PassKit API
        return $this->verifyPassKitCode($twoFactor->passkit_identifier, $code);
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

    /**
     * Create PassKit identifier (placeholder - implement actual PassKit API)
     */
    private function createPassKitIdentifier(User $user): string
    {
        // TODO: Implement actual PassKit API call
        // For now, return a placeholder
        return 'PK-' . $user->id . '-' . time();
    }

    /**
     * Verify PassKit code (placeholder - implement actual PassKit API)
     */
    private function verifyPassKitCode(string $identifier, string $code): bool
    {
        // TODO: Implement actual PassKit API verification
        // For now, return false
        return false;
    }
}

