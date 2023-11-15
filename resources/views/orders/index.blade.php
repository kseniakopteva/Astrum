<x-main-layout>
    <div class="bg-white dark:bg-neutral-800 max-w-7xl m-auto min-h-[calc(100vh-9rem)] p-6">
        <h1 class="large-title mb-4">Orders</h1>
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:bg-neutral-700 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Buyer
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Product
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Submitted email
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Details
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                    <tr class="

@if ($order->isPending()) bg-red-100 dark:bg-red-700/10
@elseif ($order->inProcess()) bg-white dark:bg-neutral-800
@elseif ($order->isComplete()) opacity-30
@endif

                    {{-- bg-white dark:bg-neutral-800 --}}
                    ">
                        <th scope="row" class="px-6 py-4 text-neutral-900 dark:text-white">
                            <x-colored-username-link size="small" :user="$order->buyer" />
                        </th>
                        <td class="px-6 py-4">
                            {{ $order->product->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $order->email }}
                        </td>
                        <td class="px-6 py-4">
                            {!! nl2br(e($order->details)) !!}
                        </td>
                        <td class="px-1 py-4">
                            <form action="{{ route('order.status') }}" method="POST">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <select name="status" onchange="this.form.submit()" class="cursor-pointer border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600 focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md">
                                    <option value="pending" @if ($order->isPending()) selected="selected" @endif>Pending</option>
                                    <option value="working" @if ($order->inProcess()) selected="selected" @endif>Working on it</option>
                                    <option value="complete" @if ($order->isComplete()) selected="selected" @endif>Complete</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

    </div>
</x-main-layout>
