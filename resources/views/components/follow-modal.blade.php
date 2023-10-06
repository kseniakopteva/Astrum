@props(['name', 'users'])

<x-modal name="open-{{ $name }}" focusable>
    <div class="p-6">
        <h2 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-4">
            {{ ucfirst($name) }}
        </h2>

        <ul class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
            @foreach ($users as $u)
                <li class="flex justify-between w-full p-2 rounded-sm">
                    <div class="flex-grow flex items-center space-x-4">
                        <img class="w-10 h-10 rounded-full" src="/images/{{ $u->image }}" alt="" width="50"
                            height="50">
                        <div>
                            <a href="/u/{{ $u->username }}">{{ $u->username }}</a><br>
                            <span>{{ $u->name }}</span>
                        </div>
                    </div>
                    @if (auth()->check() && auth()->user()->id !== $u->id)
                        @if (auth()->user()->isFollowing($u))
                            <form method="POST" action="{{ route('user.unfollow') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $u->id }}">
                                <x-secondary-button type="submit">Following</x-secondary-button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('user.follow') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $u->id }}">
                                <x-primary-button type="submit">Follow</x-primary-button>
                            </form>
                        @endif
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</x-modal>
