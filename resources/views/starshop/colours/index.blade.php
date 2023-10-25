<x-starshop-layout>
    <div class="grid grid-cols-9 gap-3 justify-center">
        @foreach ($colours as $colour)
            <div class="p-3 bg-neutral-300 bg-opacity-10 rounded-t-full flex flex-col shadow-lg">
                <div class=" h-24 w-24 mx-auto rounded-full" style="background-color: #{{ $colour->hex }}">
                </div>
                <h3 class="mt-2 text-lg">{{ $colour->name }}</h3>
                {{-- <div class="flex justify-between items-center"> --}}
                <p class="text-right mb-2 space-x-1"><span>{{ $colour->price }}</span><i class="fa-solid fa-star"></i></p>


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
