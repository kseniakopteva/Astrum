<x-main-layout>
    <header class="profile-header">
        <img src="https://placehold.co/100x100" alt="" class="profile-picture">
        <div>
            <h1 class="large-title">{{ $user->username }}</h1>
            @if ($user->name)
                <h2 class="small-title">{{ $user->name }}</h2>
            @endif
            <span class="profile-badge">Drawer</span>
            <p class="profile-bio">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maiores ipsa omnis nostrum
                illo error quos. Alias ex rem culpa sapiente. Quis iure dicta reprehenderit non a similique velit
                eligendi pariatur?</p>
        </div>
    </header>
    <div class="grid-wrapper">
        <div class="grid-column">
            <h2 class="medium-title">Posts</h2>
            @if (auth()->id() === $user->id)
                <h2 class="medium-title">New Post</h2>
                <form method="POST" action="{{ route('post.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input class="block" type="text" name="title" id="title" placeholder="Title">
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    <input type="file" name="image" id="inputImage">
                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    <textarea type="text" name="body" id="body" placeholder="Post text..."></textarea>
                    <x-input-error :messages="$errors->get('body')" class="mt-2" />
                    <button type="submit">Submit</button>
                </form>
            @endif
            <div class="posts">
                @foreach ($user->posts()->latest()->get() as $post)
                    <a href="/posts/{{ $post->slug }}">
                        <article class="post">
                            <h1 class="medium-title title">
                                {{ $post->title }}
                            </h1>
                            <img src="/images/{{ $post->image }}" alt="">
                            <div class="post-excerpt">
                                <p>{{ $post->excerpt . '...' }}</p>
                            </div>
                            <footer class="post-footer">
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </footer>
                        </article>
                    </a>
                @endforeach
            </div>
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
</x-main-layout>
