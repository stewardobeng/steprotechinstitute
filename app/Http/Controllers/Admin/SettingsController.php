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

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
