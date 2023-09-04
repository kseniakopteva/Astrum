<x-layout>
    <header class="profile-header">
        <img src="https://placehold.co/100x100" alt="" class="profile-picture">
        <div>
            <h1 class="large-title">{{ $user->username }}</h1>
            <span class="profile-badge">Drawer</span>
            <p class="profile-bio">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maiores ipsa omnis nostrum
                illo error quos. Alias ex rem culpa sapiente. Quis iure dicta reprehenderit non a similique velit
                eligendi pariatur?</p>
        </div>
    </header>
    <div class="grid-wrapper">
        <div class="grid-column">
            <h2 class="medium-title">Posts</h2>
            @foreach ($user->posts as $post)
                <a href="/posts/{{ $post->slug }}">
                    <article class="post">
                        <h1 class="medium-title title">
                            {{ $post->title }}
                        </h1>
                        <img src="https://placehold.co/900x400" alt="">
                        <div class="post-excerpt">
                            <p>{{ $post->excerpt . '...' }}</p>
                        </div>
                        <footer class="post-footer">
                            <span>{{ $post->created_at->diffForHumans() }}</span>
                        </footer>
                </a>
                </article>
            @endforeach
        </div>
        <div class="grid-column">
            <h2 class="medium-title">Notes</h2>
            @foreach ($user->notes as $note)
                <a href="/notes/{{ $note->slug }}">
                    <article class="note">
                        <p>{{ $note->body }}</p>
                        <footer class="note-footer">
                            <span>{{ $post->created_at->diffForHumans() }}</span>
                        </footer>
                    </article>
                </a>
            @endforeach
        </div>
    </div>
</x-layout>
