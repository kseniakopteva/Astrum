<x-starshop-layout>
    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-7 xl:grid-cols-9 gap-3 justify-center">
        @foreach ($colours as $colour)
            <div class="p-3 bg-white dark:bg-neutral-800 bg-opacity-60 rounded-t-full flex flex-col shadow-lg">
                <div class=" h-24 w-24 mx-auto rounded-full bg-{{ $colour->lightcolor }} dark:bg-{{ $colour->darkcolor }}">
                </div>
                <h3 class="mt-2 text-lg">{{ $colour->name }}</h3>
                {{-- <div class="flex justify-between items-center"> --}}
                <p class="text-right mb-2 space-x-1"><x-price>{{ $colour->price }}</x-price></p>


                @if (!auth()->user()->hasColour($colour->id))
                    <form action="{{ route('starshop.colours.buy') }}" class="self-center mt-auto" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $colour->id }}">
                        <x-primary-button>Buy</x-primary-button>
                    </form>
                @else
                    <x-secondary-button class="self-center mt-auto" disabled>Bought</x-secondary-button>
                @endif


            </div>
        @endforeach
    </div>
</x-starshop-layout>
