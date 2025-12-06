<x-app-layout>
    <x-slot name="title">Send Messages</x-slot>

    <!-- PageHeading -->
    <div class="flex flex-wrap justify-between gap-3 items-center">
        <h1 class="text-gray-900 dark:text-white text-2xl sm:text-3xl lg:text-4xl font-black leading-tight tracking-[-0.033em]">Send Messages</h1>
    </div>

    <div class="mt-6 sm:mt-8">
        <div class="w-full bg-white dark:bg-[#111a22] rounded-xl border border-gray-200 dark:border-[#324d67] p-4 sm:p-6">
            @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.messages.send') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Template Selection -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Use Template (Optional)</label>
                    <select name="template_id" id="template_id" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal" onchange="loadTemplate()">
                        <option value="">Select a template or compose manually...</option>
                        @foreach($templates as $category => $categoryTemplates)
                            <optgroup label="{{ $category }}">
                                @foreach($categoryTemplates as $template)
                                    <option value="{{ $template->id }}" data-subject="{{ $template->subject }}" data-message="{{ $template->message }}">
                                        {{ $template->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Select a template to auto-fill subject and message, or compose manually below</p>
                    <div class="mt-2 flex gap-2">
                        <a href="{{ route('admin.message-templates.index') }}" class="text-sm text-primary hover:underline">Manage Templates</a>
                        <span class="text-gray-400">|</span>
                        <button type="button" onclick="clearTemplate()" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Clear Template</button>
                    </div>
                </div>

                <!-- Recipient Type -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Recipient Type</label>
                    <select name="recipient_type" id="recipient_type" required class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal" onchange="toggleUserSelect()">
                        <option value="">Select recipient type...</option>
                        <option value="individual" {{ old('recipient_type') === 'individual' ? 'selected' : '' }}>Individual User</option>
                        <option value="students" {{ old('recipient_type') === 'students' ? 'selected' : '' }}>All Students</option>
                        <option value="affiliates" {{ old('recipient_type') === 'affiliates' ? 'selected' : '' }}>All Affiliates</option>
                        <option value="admins" {{ old('recipient_type') === 'admins' ? 'selected' : '' }}>All Admins</option>
                        <option value="all" {{ old('recipient_type') === 'all' ? 'selected' : '' }}>All Users</option>
                    </select>
                </div>

                <!-- Individual User Selection (shown only when individual is selected) -->
                <div id="user_select_container" style="display: none;">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Select User</label>
                    <select name="user_id" id="user_id" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal">
                        <option value="">Select a user...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Subject -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Subject</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" maxlength="255" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal" placeholder="Enter message subject">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Required if not using a template. You can use variables like @{{name}}, @{{date}}, etc.</p>
                </div>

                <!-- Message -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Message</label>
                    <textarea name="message" id="message" rows="8" minlength="10" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent px-4 py-3 text-base font-normal leading-normal resize-y" placeholder="Enter your message here...">{{ old('message') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Required if not using a template. Minimum 10 characters. You can use variables like @{{name}}, @{{date}}, @{{student_id}}, etc.</p>
                </div>

                <!-- Delivery Methods -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-3">Delivery Methods</label>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="send_email" value="1" {{ old('send_email') ? 'checked' : 'checked' }} class="rounded border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-primary focus:ring-primary">
                            <div>
                                <span class="text-gray-900 dark:text-white text-sm font-medium">Send Email</span>
                                <p class="text-gray-500 dark:text-gray-400 text-xs">Send via SMTP using branded email template</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="send_notification" value="1" {{ old('send_notification') ? 'checked' : 'checked' }} class="rounded border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-primary focus:ring-primary">
                            <div>
                                <span class="text-gray-900 dark:text-white text-sm font-medium">Send In-App Notification</span>
                                <p class="text-gray-500 dark:text-gray-400 text-xs">Create notification in user's dashboard</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-[#324d67]">
                    <button type="submit" class="flex items-center justify-center px-6 py-3 bg-primary text-white rounded-lg font-semibold hover:bg-primary/90 transition-colors">
                        <span class="material-symbols-outlined mr-2">send</span>
                        <span>Send Message</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleUserSelect() {
            const recipientType = document.getElementById('recipient_type').value;
            const userSelectContainer = document.getElementById('user_select_container');
            const userSelect = document.getElementById('user_id');
            
            if (recipientType === 'individual') {
                userSelectContainer.style.display = 'block';
                userSelect.required = true;
            } else {
                userSelectContainer.style.display = 'none';
                userSelect.required = false;
                userSelect.value = '';
            }
        }

        function loadTemplate() {
            const templateSelect = document.getElementById('template_id');
            const selectedOption = templateSelect.options[templateSelect.selectedIndex];
            
            if (selectedOption.value) {
                const subject = selectedOption.getAttribute('data-subject');
                const message = selectedOption.getAttribute('data-message');
                
                document.getElementById('subject').value = subject || '';
                document.getElementById('message').value = message || '';
                
                // Update required attributes
                document.getElementById('subject').required = false;
                document.getElementById('message').required = false;
            }
        }

        function clearTemplate() {
            document.getElementById('template_id').value = '';
            document.getElementById('subject').value = '';
            document.getElementById('message').value = '';
            document.getElementById('subject').required = true;
            document.getElementById('message').required = true;
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleUserSelect();
            
            // Check if template is pre-selected
            const templateSelect = document.getElementById('template_id');
            if (templateSelect.value) {
                loadTemplate();
            }
        });
    </script>
</x-app-layout>

