@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">

            <?php
            if ((request(['sort']) && !is_null(request(['sort'])['sort'])) || (request(['search']) && !is_null(request(['search'])['search']))) {
                $question = '?';
            } else {
                $question = '';
            }
            ?>

            @if ($paginator->onFirstPage())
                <span
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-500 bg-white dark:bg-neutral-800 dark:border-neutral-700 border border-neutral-300 cursor-default leading-5 rounded-md">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                {{-- {{ dd(str_replace('?page=', '/page/', $paginator->previousPageUrl()) . $question . \Illuminate\Support\Arr::query(['sort' => request(['sort'])['sort'] ?? null, 'search' => request(['search']) ? request(['search'])['search'] : null ])) }} --}}
                {{-- <a href="{{ $paginator->previousPageUrl() }}" --}}

                <a href="{{ str_replace('?page=', '/page/', $paginator->previousPageUrl()) . $question . \Illuminate\Support\Arr::query(['sort' => request(['sort'])['sort'] ?? null, 'search' => request(['search']) ? request(['search'])['search'] : null]) }}"
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium dark:ring-neutral-600 dark:active:bg-neutral-600 text-neutral-700 bg-white dark:bg-neutral-800 dark:border-neutral-700 border border-neutral-300 leading-5 rounded-md hover:text-neutral-500 focus:outline-none focus:ring ring-neutral-300 focus:border-blue-300 active:bg-neutral-100 active:text-neutral-700 transition ease-in-out duration-150">

                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                {{-- <a href="{{ $paginator->nextPageUrl() }}" --}}
                <a href="{{ str_replace('?page=', '/page/', $paginator->nextPageUrl()) . $question . \Illuminate\Support\Arr::query(['sort' => request(['sort'])['sort'] ?? null, 'search' => request(['search']) ? request(['search'])['search'] : null]) }}"
                    class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium dark:ring-neutral-600 dark:active:bg-neutral-600 text-neutral-700 bg-white dark:bg-neutral-800 dark:border-neutral-700 border border-neutral-300 leading-5 rounded-md hover:text-neutral-500 focus:outline-none focus:ring ring-neutral-300 focus:border-blue-300 active:bg-neutral-100 active:text-neutral-700 transition ease-in-out duration-150">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span
                    class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-neutral-500 bg-white dark:bg-neutral-800 dark:border-neutral-700 border border-neutral-300 cursor-default leading-5 rounded-md">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-neutral-700 dark:text-neutral-500 leading-5">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span
                                class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-neutral-500 bg-white dark:bg-neutral-800 dark:border-neutral-700 border border-neutral-300 cursor-default rounded-l-md leading-5"
                                aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        {{-- <a href="{{ $paginator->previousPageUrl() }}" rel="prev" --}}
                        <a href="{{ str_replace('?page=', '/page/', $paginator->previousPageUrl()) . $question . \Illuminate\Support\Arr::query(['sort' => request(['sort'])['sort'] ?? null, 'search' => request(['search']) ? request(['search'])['search'] : null]) }}"
                            rel="prev"
                            class="relative inline-flex items-center px-2 py-2 text-sm font-medium dark:ring-neutral-600 dark:active:bg-neutral-600 text-neutral-500 bg-white dark:bg-neutral-800 dark:border-neutral-700 border border-neutral-300 rounded-l-md leading-5 hover:text-neutral-400 focus:z-10 focus:outline-none focus:ring ring-neutral-300 focus:border-blue-300 active:bg-neutral-100 active:text-neutral-500 transition ease-in-out duration-150"
                            aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        {{-- @if (is_string($element)) --}}
                        @if (is_string(str_replace('?page=', '/page/', $element)))
                            <span aria-disabled="true">
                                <span
                                    class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-neutral-700 bg-white dark:bg-neutral-800 dark:border-neutral-700 border border-neutral-300 cursor-default leading-5">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        {{-- @if (is_array($element)) --}}
                        @if (is_array(str_replace('?page=', '/page/', $element)))
                            {{-- @foreach ($element as $page => $url) --}}
                            @foreach (str_replace('?page=', '/page/', $element) as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span
                                            class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-neutral-500 bg-white dark:bg-neutral-800 dark:border-neutral-700 border border-neutral-300 cursor-default leading-5">{{ $page }}</span>
                                    </span>
                                @else
                                    {{-- <a href="{{ $url }}" --}}
                                    <a href="{{ str_replace('?page=', '/page/', $url) . $question . \Illuminate\Support\Arr::query(['sort' => request(['sort'])['sort'] ?? null, 'search' => request(['search']) ? request(['search'])['search'] : null]) }}"
                                        class="relative inline-flex items-center px-4 py-2 -ml-px text-sm dark:ring-neutral-600 dark:active:bg-neutral-600 font-medium text-neutral-700 bg-white dark:bg-neutral-800 dark:border-neutral-700 border border-neutral-300 leading-5 hover:text-neutral-500 focus:z-10 focus:outline-none focus:ring ring-neutral-300 focus:border-blue-300 active:bg-neutral-100 active:text-neutral-700 transition ease-in-out duration-150"
                                        aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        {{-- <a href="{{ $paginator->nextPageUrl() }}" rel="next" --}}
                        <a href="{{ str_replace('?page=', '/page/', $paginator->nextPageUrl()) . $question . \Illuminate\Support\Arr::query(['sort' => request(['sort'])['sort'] ?? null, 'search' => request(['search']) ? request(['search'])['search'] : null]) }}"
                            rel="next"
                            class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium dark:ring-neutral-600 dark:active:bg-neutral-600 text-neutral-500 bg-white dark:bg-neutral-800 dark:border-neutral-700 border border-neutral-300 rounded-r-md leading-5 hover:text-neutral-400 focus:z-10 focus:outline-none focus:ring ring-neutral-300 focus:border-blue-300 active:bg-neutral-100 active:text-neutral-500 transition ease-in-out duration-150"
                            aria-label="{{ __('pagination.next') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span
                                class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-neutral-500 bg-white dark:bg-neutral-800 dark:border-neutral-700 border border-neutral-300 cursor-default rounded-r-md leading-5"
                                aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
