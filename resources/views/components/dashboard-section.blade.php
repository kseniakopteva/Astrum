@props(['reported_arr', 'type', 'title', 'cols'])

<style>
    .panel:hover .danger-icon {
        opacity: 50%;
    }
</style>

{{-- <h2 class="medium-title mb-2">{{ $title }}</h2> --}}
<section class="grid grid-cols-{{ $cols }} mb-5 gap-4">
    @if (!$reported_arr->isEmpty())
        @foreach ($reported_arr as $report)
            <div
                class="panel border bg-white dark:bg-neutral-800
                @if ($report->resolved) border-neutral-300 dark:border-neutral-600 opacity-50 hover:opacity-70 @else border-red-300 dark:border-red-700 @endif
                shadow-md  rounded-md p-3 flex flex-col justify-between">
                @php
                    $pretty_type = str_replace(' ', '', ucwords(str_replace('-', ' ', $report->reported_type)));
                    $class = '\App\Models\\' . $pretty_type;

                    $reported = $class::find($report->reported_id);
                    $reportee = \App\Models\User::find($report->user_id);

                $route_attr = []; @endphp

                @if (!is_null($report->reported_type))
                    @php

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
                                if (!is_null($reported)) {
                                    $name = $reported->name;
                                } else {
                                    $name = '...';
                                }
                                $route = 'starshop.wallpapers.show';
                                $route_attr['wallpaper'] = $reported->id;
                                break;
                            case 'profile-picture-frame':
                                if (!is_null($reported)) {
                                    $name = $reported->name;
                                } else {
                                    $name = '...';
                                }
                                $route = 'starshop.profile-picture-frames.show';
                                $route_attr['profile_picture_frame'] = $reported->id;
                                break;
                            case 'post-frame':
                                if (!is_null($reported)) {
                                    $name = $reported->name;
                                } else {
                                    $name = '...';
                                }
                                $route = 'starshop.post-frames.show';
                                $route_attr['post_frame'] = $reported->id;
                                break;
                        }
                    @endphp

                    <div class="relative">
                        <div class="mr-10"><span class="text-rose-500">Reported:</span>
                            <span>[{{ $pretty_type }}]</span> <a href="{{ route($route, $route_attr) }}" target="_blank">{{ $name }}</a>
                        </div>

                        <div><span class="text-rose-500">Reported by:</span>
                            <a href="{{ route('profile.index', $reportee->username) }}" target="_blank">{{ $reportee->username }}</a>
                        </div>
                        <div><span class="text-rose-500">Date of report:</span>
                            {{ date_format(new DateTime($report->created_at), 'd.m.o H:i') }} </div>
                        <div><span class="text-rose-500">Reason:</span> </div>
                        <span class="p-2 rounded-md bg-neutral-300 dark:bg-neutral-900 inline-block w-full">
                            {{ $report->reason }}
                        </span>

                        <div class="absolute top-0 right-0">
                            <a class="underline hover:text-lime-600" href="{{ route('report.show', $report->id) }}">Open</a>
                        </div>
                        @if ($reported->reports()->count() > 1)
                            <div class="absolute top-5 right-0 danger-icon"><a href="{{ route('report.show', $report->id) }}" title="Subject of more than one report">
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
                                <input type="hidden" name="type" value="{{ $report->reported_type }}">
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
                @else
                    <p class="text-xs mt-4 mb-6 italic">ORIGINAL PUBLICATION DELETED</p>
                    @if (!$report->resolved)
                        <form action="{{ route('report.delete') }}" method="post">
                            @csrf @method('post')
                            <input type="hidden" name="report_id" value="{{ $report->id }}">
                            <input type="hidden" name="type" value="{{ $report->reported_type }}">
                            <x-secondary-button type="submit" onclick="return confirm('Are you sure you want to dismiss this report?')">Remove
                                report</x-secondary-button>
                        </form>
                    @else
                    @endif
                @endif
            </div>
        @endforeach
    @else
        <div>(None)</div>
    @endif
</section>
