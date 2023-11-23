@props(['reported_arr', 'type', 'cols', 'no_button' => false])

<style>
    .panel:hover .danger-icon {
        opacity: 50%;
    }
</style>

<section>
    <div class="grid grid-cols-{{ $cols }} mb-5 gap-4">
        @if (!$reported_arr->isEmpty())
            @foreach ($reported_arr->take(4) as $report)
                <x-feed-report :report="$report" />
            @endforeach
        @else
            <div>(None)</div>
        @endif
    </div>
    @if (!$no_button)
        @if ($reported_arr->count() > 2)
            <div class=" grid place-content-center">
                <a href="{{ route('reports', ['type' => $type]) }}"
                    class="hover:text-white dark:hover:text-black inline-flex items-center px-4 py-2 bg-lime-800 dark:bg-neutral-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-neutral-800 uppercase tracking-widest hover:bg-lime-700 dark:hover:bg-white focus:bg-lime-700 dark:focus:bg-white active:bg-lime-900 dark:active:bg-neutral-300 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 transition ease-in-out duration-150">
                    See all {{ $type }} reports
                </a>
            </div>
        @endif
    @endif
</section>
