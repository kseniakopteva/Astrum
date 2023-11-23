<x-main-layout>
    <x-page-panel class="min-h-[calc(100vh-8rem)]">
        <h1 class="large-title mb-4"><a href="{{ route('mod.dashboard') }}" class="underline">Moderator Dashboard</a></h1>
        <h2 class="medium-title mb-3">{{ ucfirst($type) }} Reports</h2>

        <div class="grid grid-cols-5 mb-5 gap-4">
            @foreach ($reports as $report)
                <x-feed-report :report="$report" />
            @endforeach
        </div>

    </x-page-panel>
</x-main-layout>
