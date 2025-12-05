<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Whitelist of allowed setting keys to prevent mass assignment vulnerability
        $allowedKeys = [
            'paystack_public_key',
            'paystack_secret_key',
            'paystack_merchant_email',
            'registration_fee',
            'whatsapp_number',
            'app_name',
            'classroom_id',
            // SMTP settings
            'smtp_enabled',
            'smtp_mailer',
            'smtp_host',
            'smtp_port',
            'smtp_username',
            'smtp_password',
            'smtp_encryption',
            'smtp_from_address',
            'smtp_from_name',
        ];

        $settingsData = $request->input('settings', []);
        $groups = ['payment', 'smtp', 'sms', 'api', 'general'];
        
        foreach ($groups as $groupIndex => $groupName) {
            if (isset($settingsData[$groupIndex]) && is_array($settingsData[$groupIndex])) {
                foreach ($settingsData[$groupIndex] as $setting) {
                    if (isset($setting['key']) && isset($setting['type'])) {
                        // Validate that the key is in the whitelist
                        if (!in_array($setting['key'], $allowedKeys)) {
                            continue; // Skip unauthorized keys
                        }

                        // Validate type
                        $allowedTypes = ['string', 'integer', 'boolean', 'json'];
                        if (!in_array($setting['type'], $allowedTypes)) {
                            continue; // Skip invalid types
                        }

                        // Sanitize value based on type
                        $value = $setting['value'] ?? '';
                        if ($setting['type'] === 'integer') {
                            $value = (int) $value;
                        } elseif ($setting['type'] === 'boolean') {
                            $value = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
                        } elseif ($setting['type'] === 'json') {
                            $value = is_array($value) ? json_encode($value) : $value;
                        } else {
                            $value = (string) $value;
                            // Trim and limit length to prevent DoS
                            $value = trim($value);
                            if (strlen($value) > 1000) {
                                $value = substr($value, 0, 1000);
                            }
                        }

                        Setting::updateOrCreate(
                            ['key' => $setting['key']],
                            [
                                'value' => $value,
                                'type' => $setting['type'],
                                'group' => $groupName,
                            ]
                        );
                    }
                }
            }
        }

        // Update mail config after saving settings
        try {
            $this->updateMailConfig();
        } catch (\Exception $e) {
            \Log::warning('Failed to update mail config after settings save', ['error' => $e->getMessage()]);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    public function testMail(Request $request)
    {
        $request->validate([
            'test_email' => ['required', 'email'],
        ]);

        try {
            // Update mail config from settings
            $this->updateMailConfig();

            // Send test email
            \Illuminate\Support\Facades\Mail::raw('This is a test email from ' . \App\Models\Setting::getValue('app_name', config('app.name')) . '. Your SMTP configuration is working correctly!', function ($message) use ($request) {
                $message->to($request->test_email)
                        ->subject('Test Email - SMTP Configuration');
            });

            return redirect()->route('admin.settings.index')
                ->with('test_mail_success', 'Test email sent successfully to ' . $request->test_email . '. Please check your inbox.');
        } catch (\Exception $e) {
            \Log::error('Test email failed', [
                'email' => $request->test_email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.settings.index')
                ->with('test_mail_error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    /**
     * Update mail configuration from settings
     */
    protected function updateMailConfig(): void
    {
        $smtpEnabled = \App\Models\Setting::getValue('smtp_enabled', false);
        $smtpEnabled = filter_var($smtpEnabled, FILTER_VALIDATE_BOOLEAN);
        
        if (!$smtpEnabled) {
            \Illuminate\Support\Facades\Config::set('mail.default', 'log');
            return;
        }

        $mailHost = \App\Models\Setting::getValue('smtp_host', env('MAIL_HOST', '127.0.0.1'));
        $mailPort = \App\Models\Setting::getValue('smtp_port', env('MAIL_PORT', 2525));
        $mailUsername = \App\Models\Setting::getValue('smtp_username', env('MAIL_USERNAME'));
        $mailPassword = \App\Models\Setting::getValue('smtp_password', env('MAIL_PASSWORD'));
        $mailEncryption = \App\Models\Setting::getValue('smtp_encryption', env('MAIL_ENCRYPTION', 'tls'));
        $mailFromAddress = \App\Models\Setting::getValue('smtp_from_address', env('MAIL_FROM_ADDRESS', 'noreply@example.com'));
        $mailFromName = \App\Models\Setting::getValue('smtp_from_name', env('MAIL_FROM_NAME', 'StepProClass'));

        \Illuminate\Support\Facades\Config::set('mail.default', 'smtp');
        \Illuminate\Support\Facades\Config::set('mail.mailers.smtp', [
            'transport' => 'smtp',
            'host' => $mailHost,
            'port' => (int) $mailPort,
            'encryption' => $mailEncryption ?: null,
            'username' => $mailUsername,
            'password' => $mailPassword,
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
        ]);

        \Illuminate\Support\Facades\Config::set('mail.from', [
            'address' => $mailFromAddress,
            'name' => $mailFromName,
        ]);

        // Reconfigure the mailer to pick up new settings
        app()->forgetInstance('mailer');
    }
}
