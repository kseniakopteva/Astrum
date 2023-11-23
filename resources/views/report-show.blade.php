@php

    $pretty_type = str_replace(' ', '', ucwords(str_replace('-', ' ', $report->reported_type)));
    $class = '\App\Models\\' . $pretty_type;

    $reported = $class::find($report->reported_id);
    $reportee = \App\Models\User::find($report->user_id);

    $route_attr = [];

    switch ($report->reported_type) {
        case 'post':
            $name = $reported->title;
            $route = 'post.show';
            $route_attr['post'] = $reported->slug;
            $route_attr['author'] = $reported->author->username;
            break;
        case 'post-comment':
            $name = implode(' ', array_slice(explode(' ', $reported->body), 0, 5)) . '...';
            $route = 'post.show';
            $route_attr['post'] = $reported->post->slug;
            $route_attr['author'] = $reported->post->author->username;
            break;
        case 'user':
            $name = $reported->username;
            $route = 'profile.index';
            $route_attr['author'] = $reported->username;
            break;
        case 'note':
            $name = implode(' ', array_slice(explode(' ', $reported->notebody), 0, 5)) . '...';
            $route = 'note.show';
            $route_attr['note'] = $reported->slug;
            $route_attr['author'] = $reported->author->username;
            break;
        case 'wallpaper':
            $name = $reported->name;
            $route = 'starshop.wallpapers.show';
            $route_attr['wallpaper'] = $reported->id;
            break;
        case 'profile-picture-frame':
            $name = $reported->name;
            $route = 'starshop.profile-picture-frames.show';
            $route_attr['profile_picture_frame'] = $reported->id;
            break;
        case 'post-frame':
            $name = $reported->name;
            $route = 'starshop.post-frames.show';
            $route_attr['post_frame'] = $reported->id;
            break;
    }
@endphp

<x-main-layout>
    <x-page-panel>

        <h1 class="large-title mb-4"><a href="{{ route('mod.dashboard') }}" class="underline">Moderator Dashboard</a></h1>
        <h2 class="medium-title mb-3">Reported {{ $pretty_type }}: <a href="{{ route($route, $route_attr) }}">{{ $name }}</a></h2>

        <div
            class="panel border max-w-3xl mx-auto my-6 bg-white dark:bg-neutral-800
        @if ($report->resolved) border-neutral-300 dark:border-neutral-600 opacity-50 hover:opacity-70 @else border-red-300 dark:border-red-700 @endif
        shadow-md  rounded-md p-3 flex flex-col justify-between">


            <div class="relative">
                <span class="text-rose-500">Reported:</span> <span>[{{ $pretty_type }}]</span> <a href="{{ route($route, $route_attr) }}">{{ $name }}</a>
                <br>
                <span class="text-rose-500">Reported by:</span> <a href="{{ route('profile.index', $reportee) }}">{{ $reportee->username }}</a> <br>
                <span class="text-rose-500">Date of report:</span>
                {{ date_format(new DateTime($report->created_at), 'd.m.o H:i') }} <br>
                <span class="text-rose-500">Reason:</span> <br>
                <span class="p-2 rounded-md bg-neutral-300 dark:bg-neutral-900 inline-block w-full">
                    {{ $report->reason }}
                </span>

                @if ($reported->reports()->count() > 1)
                    <div class="absolute top-0 right-0 danger-icon"><a href="{{ route('report.show', $report->id) }}" title="Subject of more than one report">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1.5em"
                                viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <style>
                                    svg {
                                        fill: rgb(220 38 38);
                                    }
                                </style>
                                <path
                                    d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z" />
                            </svg>
                        </a>
                    </div>
                @endif

            </div>
            <div class="flex justify-between mt-2">
                @if (!$report->resolved)
                    <form action="{{ route('report.delete') }}" method="post">
                        @csrf @method('post')
                        <input type="hidden" name="report_id" value="{{ $report->id }}">
                        <x-secondary-button type="submit" onclick="return confirm('Are you sure you want to dismiss this report?')">Dismiss</x-secondary-button>
                    </form>
                    @if ($report->reported_type != 'user')
                        <form action="{{ route('report.approve') }}" method="POST">
                            @csrf @method('post')
                            <input type="hidden" name="report_id" value="{{ $report->id }}">
                            <input type="hidden" name="reported_id" value="{{ $reported->id }}">
                            <input type="hidden" name="class" value="{{ $class }}">

                            <x-danger-button onclick="return confirm('Are you sure you want to remove this {{ str_replace('-', ' ', $report->reported_type) }}?')">Remove</x-danger-button>
                        </form>
                    @else
                        <x-danger-button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'ban-{{ $reported->id }}')">Ban</x-danger-button>
                    @endif
                @else
                    <div class="flex justify-center w-full">
                        <p
                            class="inline-flex items-center px-4 py-2 bg-white dark:bg-neutral-800
                font-semibold text-xs text-neutral-700 dark:text-neutral-300 uppercase tracking-widest">
                            Resolved</p>
                    </div>
                @endif
            </div>
        </div>

        <h2 class="medium-title mb-2">Other Reports Of This
            {{ str_replace(' ', '', ucwords(str_replace('-', ' ', $report->reported_type))) }}</h2>

        @php
            $other_reports_of_this = \App\Models\Report::where('reported_type', $report->reported_type)
                ->where('reported_id', $report->reported_id)
                ->where('id', '!=', $report->id)
                ->orderBy('resolved', 'ASC')
                ->orderBy('created_at', 'ASC')
                ->get();
        @endphp

        <x-dashboard-section cols="4" :reported_arr="$other_reports_of_this" type="{{ $report->reported_type }}" :no_button="true" />

        <h2 class="medium-title mb-2">Other Reports mentioning
            @if ($report->reported_type != 'user')
                <a href="{{ route('profile.index', $reported->author->username) }}">{{ $reported->author->username }}</a>
            @else
                <a href="{{ route('profile.index', $reported->username) }}">{{ $reported->username }}</a>
            @endif
        </h2>

        @php
            $other_reports = \App\Models\Report::where('reported_type', '!=', $report->reported_type)
                ->orderBy('resolved', 'ASC')
                ->orderBy('created_at', 'ASC')
                ->get();
        @endphp

        <x-dashboard-section cols="4" :reported_arr="$other_reports" type="{{ $report->reported_type }}" :no_button="true" />

    </x-page-panel>

    @if ($report->reported_type == 'user')
        <x-user-ban-modal :user="$reported"></x-user-ban-modal>
    @endif

    @if ($other_reports->count() > 0)
        @foreach ($other_reports as $report)
            @if (!is_null($report->reported_type) && $report->reported_type == 'user')
                <x-user-ban-modal :user="\App\Models\User::find($report->reported_id)"></x-user-ban-modal>
            @break
        @endif
    @endforeach
@endif

</x-main-layout>
