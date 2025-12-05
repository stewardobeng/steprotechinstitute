<x-app-layout>
    <x-slot name="title">Settings Management</x-slot>


    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4 sm:space-y-6">
        @csrf
        
        @foreach(['payment', 'smtp', 'sms', 'api', 'general'] as $group)
            <div class="rounded-lg bg-white dark:bg-[#111a22] overflow-hidden border border-gray-200 dark:border-[#324d67]">
                <!-- Settings Group Header -->
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 dark:border-[#324d67] bg-gray-50 dark:bg-gray-800/50">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary text-2xl">
                            @if($group === 'payment') credit_card
                            @elseif($group === 'smtp') mail
                            @elseif($group === 'sms') sms
                            @elseif($group === 'api') api
                            @else settings
                            @endif
                        </span>
                        <div>
                            <h2 class="text-gray-900 dark:text-white text-xl font-bold leading-tight capitalize">{{ $group }} Settings</h2>
                            <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal">
                                @if($group === 'payment') Configure payment gateway and transaction settings
                                @elseif($group === 'smtp') Configure email server and SMTP settings
                                @elseif($group === 'sms') Configure SMS service provider settings
                                @elseif($group === 'api') Configure third-party API integrations
                                @else General application settings and preferences
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Settings Content -->
                <div class="p-6">
                    @php
                        $groupSettings = $settings->get($group, collect());
                    @endphp
                    
                    @php
                        $groupIndex = array_search($group, ['payment', 'smtp', 'sms', 'api', 'general']);
                    @endphp
                    @if($groupSettings->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($groupSettings as $settingIndex => $setting)
                                <div class="flex flex-col gap-2">
                                    <label for="setting_{{ $setting->id }}" class="text-gray-700 dark:text-gray-300 text-sm font-medium leading-normal">
                                        {{ ucfirst(str_replace('_', ' ', $setting->key)) }}
                                        @if($setting->description)
                                            <span class="text-gray-500 dark:text-[#92adc9] font-normal">({{ $setting->description }})</span>
                                        @endif
                                    </label>
                                    @if($setting->type === 'boolean')
                                        <select name="settings[{{ $groupIndex }}][{{ $settingIndex }}][value]" id="setting_{{ $setting->id }}" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal">
                                            <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>No</option>
                                        </select>
                                    @elseif($setting->type === 'json')
                                        <textarea name="settings[{{ $groupIndex }}][{{ $settingIndex }}][value]" id="setting_{{ $setting->id }}" rows="4" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent px-4 py-2 text-base font-normal leading-normal font-mono text-sm">{{ $setting->value }}</textarea>
                                    @else
                                        <input 
                                            type="{{ $setting->type === 'integer' ? 'number' : ($setting->key === 'paystack_secret_key' || str_contains($setting->key, 'password') || str_contains($setting->key, 'secret') ? 'password' : 'text') }}" 
                                            name="settings[{{ $groupIndex }}][{{ $settingIndex }}][value]" 
                                            id="setting_{{ $setting->id }}" 
                                            value="{{ $setting->value }}" 
                                            step="{{ $setting->type === 'integer' ? '1' : '0.01' }}"
                                            class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal"
                                            placeholder="Enter {{ str_replace('_', ' ', $setting->key) }}"
                                        />
                                    @endif
                                    <input type="hidden" name="settings[{{ $groupIndex }}][{{ $settingIndex }}][key]" value="{{ $setting->key }}">
                                    <input type="hidden" name="settings[{{ $groupIndex }}][{{ $settingIndex }}][type]" value="{{ $setting->type }}">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 dark:bg-gray-800/30 rounded-lg p-4 border border-gray-200 dark:border-[#324d67]">
                            <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal mb-4">No settings configured for this group. Settings will be created automatically when you save.</p>
                            
                            @if($group === 'payment')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="flex flex-col gap-2">
                                        <label for="paystack_public_key" class="text-gray-700 dark:text-gray-300 text-sm font-medium leading-normal">PayStack Public Key</label>
                                        <input type="text" id="paystack_public_key" name="settings[0][0][value]" value="{{ \App\Models\Setting::getValue('paystack_public_key', env('PAYSTACK_PUBLIC_KEY')) }}" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal" placeholder="pk_test_...">
                                        <input type="hidden" name="settings[0][0][key]" value="paystack_public_key">
                                        <input type="hidden" name="settings[0][0][type]" value="string">
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="paystack_secret_key" class="text-gray-700 dark:text-gray-300 text-sm font-medium leading-normal">PayStack Secret Key</label>
                                        <input type="password" id="paystack_secret_key" name="settings[0][1][value]" value="{{ \App\Models\Setting::getValue('paystack_secret_key', env('PAYSTACK_SECRET_KEY')) }}" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal" placeholder="sk_test_...">
                                        <input type="hidden" name="settings[0][1][key]" value="paystack_secret_key">
                                        <input type="hidden" name="settings[0][1][type]" value="string">
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="paystack_merchant_email" class="text-gray-700 dark:text-gray-300 text-sm font-medium leading-normal">PayStack Merchant Email</label>
                                        <input type="email" id="paystack_merchant_email" name="settings[0][2][value]" value="{{ \App\Models\Setting::getValue('paystack_merchant_email', env('PAYSTACK_MERCHANT_EMAIL')) }}" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal" placeholder="merchant@example.com">
                                        <input type="hidden" name="settings[0][2][key]" value="paystack_merchant_email">
                                        <input type="hidden" name="settings[0][2][type]" value="string">
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="registration_fee" class="text-gray-700 dark:text-gray-300 text-sm font-medium leading-normal">Registration Fee (GHS)</label>
                                        <input type="number" id="registration_fee" name="settings[0][3][value]" value="{{ \App\Models\Setting::getValue('registration_fee', 150) }}" step="0.01" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal">
                                        <input type="hidden" name="settings[0][3][key]" value="registration_fee">
                                        <input type="hidden" name="settings[0][3][type]" value="integer">
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="commission_amount" class="text-gray-700 dark:text-gray-300 text-sm font-medium leading-normal">Commission Amount (GHS)</label>
                                        <input type="number" id="commission_amount" name="settings[0][4][value]" value="{{ \App\Models\Setting::getValue('commission_amount', 40) }}" step="0.01" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal">
                                        <input type="hidden" name="settings[0][4][key]" value="commission_amount">
                                        <input type="hidden" name="settings[0][4][type]" value="integer">
                                    </div>
                                </div>
                            @elseif($group === 'smtp')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="flex flex-col gap-2">
                                        <label for="smtp_enabled" class="text-gray-700 dark:text-gray-300 text-sm font-medium leading-normal">Enable SMTP</label>
                                        <select id="smtp_enabled" name="settings[1][0][value]" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal">
                                            <option value="1" {{ \App\Models\Setting::getValue('smtp_enabled', false) ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ !\App\Models\Setting::getValue('smtp_enabled', false) ? 'selected' : '' }}>No</option>
                                        </select>
                                        <input type="hidden" name="settings[1][0][key]" value="smtp_enabled">
                                        <input type="hidden" name="settings[1][0][type]" value="boolean">
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="smtp_host" class="text-gray-700 dark:text-gray-300 text-sm font-medium leading-normal">SMTP Host</label>
                                        <input type="text" id="smtp_host" name="settings[1][1][value]" value="{{ \App\Models\Setting::getValue('smtp_host', env('MAIL_HOST', 'smtp.mailtrap.io')) }}" placeholder="smtp.mailtrap.io" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal">
                                        <input type="hidden" name="settings[1][1][key]" value="smtp_host">
                                        <input type="hidden" name="settings[1][1][type]" value="string">
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="smtp_port" class="text-gray-700 dark:text-gray-300 text-sm font-medium leading-normal">SMTP Port</label>
                                        <input type="number" id="smtp_port" name="settings[1][2][value]" value="{{ \App\Models\Setting::getValue('smtp_port', env('MAIL_PORT', 2525)) }}" placeholder="2525" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal">
                                        <input type="hidden" name="settings[1][2][key]" value="smtp_port">
                                        <input type="hidden" name="settings[1][2][type]" value="integer">
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="smtp_encryption" class="text-gray-700 dark:text-gray-300 text-sm font-medium leading-normal">Encryption</label>
                                        <select id="smtp_encryption" name="settings[1][3][value]" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal">
                                            <option value="tls" {{ \App\Models\Setting::getValue('smtp_encryption', env('MAIL_ENCRYPTION', 'tls')) === 'tls' ? 'selected' : '' }}>TLS</option>
                                            <option value="ssl" {{ \App\Models\Setting::getValue('smtp_encryption', env('MAIL_ENCRYPTION', 'tls')) === 'ssl' ? 'selected' : '' }}>SSL</option>
                                            <option value="" {{ empty(\App\Models\Setting::getValue('smtp_encryption', env('MAIL_ENCRYPTION', 'tls'))) ? 'selected' : '' }}>None</option>
                                        </select>
                                        <input type="hidden" name="settings[1][3][key]" value="smtp_encryption">
                                        <input type="hidden" name="settings[1][3][type]" value="string">
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="smtp_username" class="text-gray-700 dark:text-gray-300 text-sm font-medium leading-normal">SMTP Username</label>
                                        <input type="text" id="smtp_username" name="settings[1][4][value]" value="{{ \App\Models\Setting::getValue('smtp_username', env('MAIL_USERNAME')) }}" placeholder="your-username" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal">
                                        <input type="hidden" name="settings[1][4][key]" value="smtp_username">
                                        <input type="hidden" name="settings[1][4][type]" value="string">
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="smtp_password" class="text-gray-700 dark:text-gray-300 text-sm font-medium leading-normal">SMTP Password</label>
                                        <input type="password" id="smtp_password" name="settings[1][5][value]" value="{{ \App\Models\Setting::getValue('smtp_password', env('MAIL_PASSWORD')) }}" placeholder="your-password" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal">
                                        <input type="hidden" name="settings[1][5][key]" value="smtp_password">
                                        <input type="hidden" name="settings[1][5][type]" value="string">
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="smtp_from_address" class="text-gray-700 dark:text-gray-300 text-sm font-medium leading-normal">From Email Address</label>
                                        <input type="email" id="smtp_from_address" name="settings[1][6][value]" value="{{ \App\Models\Setting::getValue('smtp_from_address', env('MAIL_FROM_ADDRESS', 'noreply@example.com')) }}" placeholder="noreply@example.com" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal">
                                        <input type="hidden" name="settings[1][6][key]" value="smtp_from_address">
                                        <input type="hidden" name="settings[1][6][type]" value="string">
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="smtp_from_name" class="text-gray-700 dark:text-gray-300 text-sm font-medium leading-normal">From Name</label>
                                        <input type="text" id="smtp_from_name" name="settings[1][7][value]" value="{{ \App\Models\Setting::getValue('smtp_from_name', env('MAIL_FROM_NAME', 'StepProClass')) }}" placeholder="StepProClass" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal">
                                        <input type="hidden" name="settings[1][7][key]" value="smtp_from_name">
                                        <input type="hidden" name="settings[1][7][type]" value="string">
                                    </div>
                                </div>
                            @elseif($group === 'general')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="flex flex-col gap-2">
                                        <label for="app_name" class="text-gray-700 dark:text-gray-300 text-sm font-medium leading-normal">App Name</label>
                                        <input type="text" id="app_name" name="settings[4][0][value]" value="{{ \App\Models\Setting::getValue('app_name', config('app.name', 'Laravel')) }}" placeholder="My Application" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal">
                                        <input type="hidden" name="settings[4][0][key]" value="app_name">
                                        <input type="hidden" name="settings[4][0][type]" value="string">
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="whatsapp_number" class="text-gray-700 dark:text-gray-300 text-sm font-medium leading-normal">WhatsApp Number</label>
                                        <input type="text" id="whatsapp_number" name="settings[4][1][value]" value="{{ \App\Models\Setting::getValue('whatsapp_number', env('WHATSAPP_NUMBER', '233244775129')) }}" placeholder="233244775129" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal">
                                        <input type="hidden" name="settings[4][1][key]" value="whatsapp_number">
                                        <input type="hidden" name="settings[4][1][type]" value="string">
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        <!-- Save Button -->
        <div class="flex justify-end gap-4 pt-4">
            <button type="button" onclick="location.reload()" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-gray-200 dark:hover:bg-white/20">
                <span class="truncate">Cancel</span>
            </button>
            <button type="submit" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-primary/90">
                <span class="truncate">Save All Settings</span>
                <span class="material-symbols-outlined text-xl ml-2">save</span>
            </button>
        </div>
    </form>
</x-app-layout>
