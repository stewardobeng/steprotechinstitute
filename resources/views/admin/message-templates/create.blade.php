<x-app-layout>
    <x-slot name="title">Create Message Template</x-slot>

    <!-- PageHeading -->
    <div class="flex flex-wrap justify-between gap-3 items-center">
        <h1 class="text-gray-900 dark:text-white text-2xl sm:text-3xl lg:text-4xl font-black leading-tight tracking-[-0.033em]">Create Message Template</h1>
        <a href="{{ route('admin.message-templates.index') }}" class="flex items-center justify-center px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg font-semibold hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
            <span class="material-symbols-outlined mr-2">arrow_back</span>
            <span>Back</span>
        </a>
    </div>

    <div class="mt-6 sm:mt-8">
        <div class="w-full bg-white dark:bg-[#111a22] rounded-xl border border-gray-200 dark:border-[#324d67] p-4 sm:p-6">
            <form action="{{ route('admin.message-templates.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Template Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="255" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal" placeholder="e.g., Welcome Message">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Category</label>
                    <select name="category" id="category" required class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal">
                        <option value="">Select category...</option>
                        @foreach($allCategories as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Or type a new category name</p>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Description (Optional)</label>
                    <input type="text" name="description" id="description" value="{{ old('description') }}" maxlength="500" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal" placeholder="Brief description of when to use this template">
                </div>

                <!-- Subject -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Subject</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required maxlength="255" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal" placeholder="Email subject line">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">You can use variables like @{{name}}, @{{date}}, etc.</p>
                </div>

                <!-- Message -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Message</label>
                    <textarea name="message" id="message" rows="12" required minlength="10" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent px-4 py-3 text-base font-normal leading-normal resize-y font-mono text-sm" placeholder="Enter your message template here...">{{ old('message') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Use variables like @{{name}}, @{{date}}, @{{student_id}}, etc. They will be replaced with actual values when sending.</p>
                </div>

                <!-- Variables -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Available Variables (Optional)</label>
                    <input type="text" name="variables" id="variables" value="{{ old('variables') }}" class="w-full rounded-lg border border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent h-10 px-4 text-base font-normal leading-normal" placeholder="name, date, student_id (comma-separated)">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">List variables used in this template (for reference only)</p>
                </div>

                <!-- Active Status -->
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-[#324d67] bg-white dark:bg-[#111a22] text-primary focus:ring-primary">
                        <span class="text-gray-900 dark:text-white text-sm font-medium">Active (template will be available for selection)</span>
                    </label>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-[#324d67]">
                    <a href="{{ route('admin.message-templates.index') }}" class="flex items-center justify-center px-6 py-3 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg font-semibold hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        <span>Cancel</span>
                    </a>
                    <button type="submit" class="flex items-center justify-center px-6 py-3 bg-primary text-white rounded-lg font-semibold hover:bg-primary/90 transition-colors">
                        <span class="material-symbols-outlined mr-2">save</span>
                        <span>Create Template</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

