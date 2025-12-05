<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check if SMTP is enabled
        $smtpEnabled = Setting::getValue('smtp_enabled', false);
        $smtpEnabled = filter_var($smtpEnabled, FILTER_VALIDATE_BOOLEAN);
        
        if (!$smtpEnabled) {
            Log::warning('Password reset attempted but SMTP is disabled', ['email' => $request->email]);
            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => 'Email service is currently disabled. Please contact administrator.']);
        }

        // Update mail configuration from settings before sending
        try {
            $this->updateMailConfig();
        } catch (\Exception $e) {
            Log::error('Failed to update mail config for password reset', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);
            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => 'Email configuration error. Please contact administrator.']);
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status == Password::RESET_LINK_SENT) {
                Log::info('Password reset link sent successfully', ['email' => $request->email]);
                return back()->with('status', 'We have emailed your password reset link. Please check your inbox.');
            } else {
                Log::warning('Password reset link failed', [
                    'email' => $request->email,
                    'status' => $status
                ]);
                return back()->withInput($request->only('email'))
                            ->withErrors(['email' => 'We were unable to send a password reset link. Please check your email address or try again later.']);
            }
        } catch (\Exception $e) {
            Log::error('Exception while sending password reset link', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => 'Failed to send email. Please check your SMTP settings or contact administrator.']);
        }
    }

    /**
     * Update mail configuration from settings
     */
    protected function updateMailConfig(): void
    {
        try {
            // Check if SMTP is enabled
            $smtpEnabled = Setting::getValue('smtp_enabled', false);
            $smtpEnabled = filter_var($smtpEnabled, FILTER_VALIDATE_BOOLEAN);
            
            if (!$smtpEnabled) {
                // If SMTP is disabled, use log driver
                Config::set('mail.default', 'log');
                return;
            }

            $mailHost = Setting::getValue('smtp_host', env('MAIL_HOST', '127.0.0.1'));
            $mailPort = Setting::getValue('smtp_port', env('MAIL_PORT', 2525));
            $mailUsername = Setting::getValue('smtp_username', env('MAIL_USERNAME'));
            $mailPassword = Setting::getValue('smtp_password', env('MAIL_PASSWORD'));
            $mailEncryption = Setting::getValue('smtp_encryption', env('MAIL_ENCRYPTION', 'tls'));
            $mailFromAddress = Setting::getValue('smtp_from_address', env('MAIL_FROM_ADDRESS', 'noreply@example.com'));
            $mailFromName = Setting::getValue('smtp_from_name', env('MAIL_FROM_NAME', 'StepProClass'));

            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp', [
                'transport' => 'smtp',
                'host' => $mailHost,
                'port' => (int) $mailPort,
                'encryption' => $mailEncryption ?: null,
                'username' => $mailUsername,
                'password' => $mailPassword,
                'timeout' => null,
                'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
            ]);

            Config::set('mail.from', [
                'address' => $mailFromAddress,
                'name' => $mailFromName,
            ]);

            // Reconfigure the mailer to pick up new settings
            app()->forgetInstance('mailer');
        } catch (\Exception $e) {
            Log::error('Failed to update mail config for password reset', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
