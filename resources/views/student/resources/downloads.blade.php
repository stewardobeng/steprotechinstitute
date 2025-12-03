<x-app-layout>
    <x-slot name="title">Downloadable Resources</x-slot>

    <!-- PageHeading -->
    <div class="flex flex-wrap justify-between gap-3 items-center">
        <h1 class="text-gray-900 dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">Resources</h1>
    </div>

    <!-- Navigation Tabs -->
    <div class="mt-6 border-b border-gray-200 dark:border-[#324d67]">
        <nav class="flex gap-6" aria-label="Tabs">
            <a href="{{ route('student.resources.recordings') }}" 
               class="border-b-2 {{ request()->routeIs('student.resources.recordings') ? 'border-primary text-primary dark:text-primary' : 'border-transparent text-gray-500 dark:text-[#92adc9] hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} py-4 px-1 text-sm font-medium leading-normal transition">
                Class Recordings
            </a>
            <a href="{{ route('student.resources.downloads') }}" 
               class="border-b-2 {{ request()->routeIs('student.resources.downloads') ? 'border-primary text-primary dark:text-primary' : 'border-transparent text-gray-500 dark:text-[#92adc9] hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }} py-4 px-1 text-sm font-medium leading-normal transition">
                Downloads
            </a>
        </nav>
    </div>

    <div class="mt-8">
        @if(count($resources) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($resources as $resource)
                    <div class="flex flex-col rounded-xl border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] p-5">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-primary/10 dark:bg-primary/20 flex items-center justify-center">
                                @if($resource->type === 'pdf')
                                    <span class="material-symbols-outlined text-primary text-2xl">picture_as_pdf</span>
                                @elseif($resource->type === 'ebook')
                                    <span class="material-symbols-outlined text-primary text-2xl">menu_book</span>
                                @else
                                    <span class="material-symbols-outlined text-primary text-2xl">description</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-gray-900 dark:text-white text-lg font-bold leading-tight mb-1 truncate">{{ $resource->title }}</h3>
                                <p class="text-gray-500 dark:text-[#92adc9] text-xs font-normal leading-normal">
                                    {{ strtoupper($resource->type ?? 'file') }} • {{ $resource->file_size ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal mb-4 line-clamp-2">
                            {{ $resource->description ?? 'No description available.' }}
                        </p>
                        <a href="#" 
                           class="flex items-center justify-center gap-2 rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-primary/90 transition">
                            <span class="material-symbols-outlined text-lg">download</span>
                            <span>Download</span>
                        </a>
                        <p class="text-gray-400 dark:text-gray-600 text-xs font-normal leading-normal mt-3">
                            Published: {{ $resource->created_at->format('M d, Y') }}
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center rounded-xl border border-gray-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] p-12">
                <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 text-6xl mb-4">folder_off</span>
                <h3 class="text-gray-900 dark:text-white text-xl font-bold leading-tight mb-2">No Resources Available</h3>
                <p class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal text-center max-w-md">
                    Downloadable resources such as ebooks, PDFs, and other materials will appear here once they are published by the administrator. Check back soon!
                </p>
            </div>
        @endif
    </div>
</x-app-layout>

