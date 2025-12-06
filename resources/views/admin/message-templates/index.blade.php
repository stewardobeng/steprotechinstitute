<x-app-layout>
    <x-slot name="title">Message Templates</x-slot>

    <!-- PageHeading -->
    <div class="flex flex-wrap justify-between gap-3 items-center">
        <h1 class="text-gray-900 dark:text-white text-2xl sm:text-3xl lg:text-4xl font-black leading-tight tracking-[-0.033em]">Message Templates</h1>
        <a href="{{ route('admin.message-templates.create') }}" class="flex items-center justify-center px-4 py-2 bg-primary text-white rounded-lg font-semibold hover:bg-primary/90 transition-colors">
            <span class="material-symbols-outlined mr-2">add</span>
            <span>New Template</span>
        </a>
    </div>

    <div class="mt-6 sm:mt-8">
        @if(session('success'))
            <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($templates->isEmpty())
            <div class="bg-white dark:bg-[#111a22] rounded-xl border border-gray-200 dark:border-[#324d67] p-8 text-center">
                <p class="text-gray-500 dark:text-gray-400">No message templates found. Create your first template to get started.</p>
                <a href="{{ route('admin.message-templates.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary text-white rounded-lg font-semibold hover:bg-primary/90 transition-colors mt-4">
                    <span class="material-symbols-outlined mr-2">add</span>
                    <span>Create Template</span>
                </a>
            </div>
        @else
            @foreach($templates as $category => $categoryTemplates)
                <div class="mb-6 bg-white dark:bg-[#111a22] rounded-xl border border-gray-200 dark:border-[#324d67] overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-gray-200 dark:border-[#324d67] bg-gray-50 dark:bg-gray-800">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $category }}</h2>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-[#324d67]">
                        @foreach($categoryTemplates as $template)
                            <div class="p-4 sm:p-5 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $template->name }}</h3>
                                            @if(!$template->is_active)
                                                <span class="px-2 py-1 text-xs font-medium bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded">Inactive</span>
                                            @endif
                                        </div>
                                        @if($template->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $template->description }}</p>
                                        @endif
                                        <p class="text-sm text-gray-500 dark:text-gray-500">
                                            <strong>Subject:</strong> {{ $template->subject }}
                                        </p>
                                        @if($template->variables)
                                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                                <strong>Variables:</strong> {{ implode(', ', $template->variables) }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.message-templates.edit', $template) }}" class="px-3 py-2 text-sm font-medium text-primary hover:bg-primary/10 dark:hover:bg-primary/20 rounded-lg transition-colors">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </a>
                                        <form action="{{ route('admin.message-templates.destroy', $template) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this template?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</x-app-layout>

