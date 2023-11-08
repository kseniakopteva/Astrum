@props(['user'])

<x-modal name="ban" focusable>
    <div class="p-6">
        <h2 class="text-lg font-medium text-red-500 dark:text-neutral-100 mb-4">
            Ban User <span class="font-bold">{{ $user->username }}</span>
        </h2>

        <form action="{{ route('ban') }}" method="POST">
            @csrf @method('post')

            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <x-input-label for="duration">Ban for: </x-input-label>
            <select name="duration" id="duration"
                class="my-2 border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md">
                <option value="1">1 Day</option>
                <option value="7">7 Days</option>
                <option value="30">30 Days</option>
                <option value="forever" @mod disabled @endmod>Forever (Admins
                    only)</option>
            </select>

            <x-input-label for="reason">Reason for ban:</x-input-label>
            <x-textarea class="w-full mt-2" name="reason" id="reason"></x-textarea>

            <x-danger-button class="mt-4">Ban</x-danger-button>
        </form>

    </div>
</x-modal>
