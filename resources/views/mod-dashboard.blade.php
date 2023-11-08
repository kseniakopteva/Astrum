<x-main-layout>
    <div class="bg-white dark:bg-neutral-800 max-w-7xl m-auto min-h-[calc(100vh-9rem)] p-6">
        <h1 class="large-title mb-4">Moderator Dashboard</h1>

        <div class="grid grid-cols-2 gap-10">

            <div>
                <h2 class="medium-title mb-3">Reports</h2>
                @php
                $reported_arr = [
                [
                'arr' => $reported_users,
                'type' => 'user',
                'title' => 'Reported Users',
                ],
                [
                'arr' => $reported_posts,
                'type' => 'post',
                'title' => 'Reported Posts',
                ],
                [
                'arr' => $reported_post_comments,
                'type' => 'post-comment',
                'title' => 'Reported Post Comments',
                ],
                [
                'arr' => $reported_notes,
                'type' => 'note',
                'title' => 'Reported Notes',
                ],
                [
                'arr' => $reported_wallpapers,
                'type' => 'wallpaper',
                'title' => 'Reported Wallpapers',
                ],
                [
                'arr' => $reported_profile_picture_frames,
                'type' => 'profile-picture-frame',
                'title' => 'Reported Profile Picture Frames',
                ],
                [
                'arr' => $reported_post_frames,
                'type' => 'post-frame',
                'title' => 'Reported Post Frames',
                ],
                ];
                @endphp

                <div class="space-y-2">
                    @foreach ($reported_arr as $reported_type)
                    <div x-data="{ open: false }" class="w-full bg-neutral-50 rounded-md @if ($reported_type['arr']->where('resolved', 0)->count() > 0) shadow-md @endif">
                        <button @click="open=!open" @if ($reported_type['arr']->where('resolved', 0)->count() <= 0) disabled @endif x-html="open ? '<span>{{ $reported_type['title'] }} ({{ $reported_type['arr']->where('resolved', 0)->count() }})</span> @if ($reported_type['arr']->where('resolved', 0)->where('resolved', 0)->count() > 0)<span>-</span>@endif' :'<span>{{ $reported_type['title'] }} ({{ $reported_type['arr']->where('resolved', 0)->count() }})</span> @if ($reported_type['arr']->where('resolved', 0)->count() > 0)<span>+</span>@endif' " class="py-2 px-3 flex justify-between w-full @if ($reported_type['arr']->where('resolved', 0)->count() > 0) text-black hover:text-lime-600 @else text-neutral-400 @endif text-lg"></button>

                        @if ($reported_type['arr']->count() > 0)
                        <div x-show="open" x-cloak class="mx-4 py-4" x-transition>
                            <x-dashboard-section cols="2" :reported_arr="$reported_type['arr']" :type="$reported_type['type']" :title="$reported_type['title']" />
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            <div>
                <h2 class="medium-title mb-3">Banned users</h2>

                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Username
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    from
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    to
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    reason
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php

                            $banned_users = \App\Models\Ban::where('start_date', '<', \Carbon\Carbon::now()->timezone('Europe/Riga')->toDateTimeString())
                                ->where('end_date', '>', \Carbon\Carbon::now()->timezone('Europe/Riga')->toDateTimeString())->get();

                                @endphp
                                @foreach ($banned_users as $ban)

                                <tr class="bg-white dark:bg-gray-800">
                                    <th scope="row" class="px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ \App\Models\User::find($ban->user_id)->username }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ date_format(new DateTime($ban->start_date), 'd.m.y H:i'); }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ date_format(new DateTime($ban->end_date), 'd.m.y H:i'); }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $ban->reason }}
                                    </td>
                                </tr>
                                @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-main-layout>
