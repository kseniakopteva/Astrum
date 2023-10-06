<button
    {{ $attributes->merge([
        'class' =>
            'cursor-pointer inline-flex items-center px-4 py-2 hover:text-white bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 transition ease-in-out duration-150',
    ]) }}>{{ $slot }}</button>
