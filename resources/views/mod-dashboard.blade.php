<x-main-layout>
    <div class="bg-white dark:bg-neutral-800 max-w-7xl m-auto min-h-[calc(100vh-9rem)] p-6">
        <h1 class="large-title mb-4">Moderator Dashboard</h1>
        <x-dashboard-section :reported_arr="$reported_users" type="user" />
        <x-dashboard-section :reported_arr="$reported_posts" type="post" />
        <x-dashboard-section :reported_arr="$reported_post_comments" type="post-comment" />
        <x-dashboard-section :reported_arr="$reported_notes" type="note" />
        <x-dashboard-section :reported_arr="$reported_wallpapers" type="wallpaper" />
        <x-dashboard-section :reported_arr="$reported_profile_picture_frames" type="profile-picture-frame" />
        <x-dashboard-section :reported_arr="$reported_post_frames" type="post-frame" />
    </div>
</x-main-layout>
