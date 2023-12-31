<x-main-layout>
    <x-page-panel>
        @if (auth()->user()->isCreatorOrMore())
            <h1 class="large-title mb-4">Orders</h1>
            <div class="relative overflow-x-auto">
                @if (!$orders->isEmpty())
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
                                <tr
                                    class="
                                @if ($order->isPending() || $order->isCompleteRejected()) bg-red-100 dark:bg-red-700/10
                                @elseif ($order->inProcess()) bg-white dark:bg-neutral-800
                                @elseif ($order->isCompleteDone() || $order->isRejected()) opacity-50 @endif
                        ">
                                    <th scope="row" class="px-6 py-4 text-neutral-900 dark:text-white">
                                        <x-colored-username-link size="small" :user="$order->buyer" />
                                    </th>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('profile.shop', $order->seller->username) }}">{{ $order->product->name }}</a>
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
                                            <select name="status" onchange="this.form.submit()" @if ($order->isCompleteDone() || $order->isRejected()) disabled class=" @else class="cursor-pointer @endif
                                                border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 focus:border-lime-500 dark:focus:border-lime-600
                                                focus:ring-lime-500 dark:focus:ring-lime-600 rounded-md">
                                                <option value="pending" @if ($order->isPending() || $order->isCompleteRejected()) selected="selected" @endif>Pending</option>
                                                <option value="in_process" @if ($order->inProcess()) selected="selected" @endif>Working on it</option>
                                                <option value="rejected" @if ($order->isRejected()) selected="selected" @endif>Rejected</option>
                                                <option value="complete" @if ($order->isCompletePending() || $order->isCompleteDone()) selected="selected" @endif>Complete</option>
                                            </select>
                                        </form>
                                        @if ($order->isCompletePending())
                                            <p class="italic">(Waiting for confirmation...)</p>
                                        @elseif ($order->isCompleteRejected())
                                            <p class="italic">(Status 'complete' has been rejected!<br> Have you sent the product?..)</p>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-lg text-neutral-400 dark:text-neutral-500">(None)</p>
                @endif
            </div>
        @endif

        <h1 class="large-title mb-4 mt-8">My Orders</h1>
        <div class="relative overflow-x-auto">
            @if (!$my_orders->isEmpty())
                <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                    <thead class="text-xs text-neutral-700 uppercase bg-neutral-50 dark:bg-neutral-700 dark:text-neutral-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Seller
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
                        @foreach ($my_orders as $order)
                            <tr
                                class="
                                @if ($order->isPending() || $order->isCompleteRejected()) bg-red-100 dark:bg-red-700/10
                                @elseif ($order->inProcess()) bg-white dark:bg-neutral-800
                                @elseif ($order->isCompleteDone() || $order->isRejected()) opacity-50 @endif
                            ">
                                <th scope="row" class="px-6 py-4 text-neutral-900 dark:text-white">
                                    <x-colored-username-link size="small" :user="$order->seller" />
                                </th>
                                <td class="px-6 py-4">
                                    <a href="{{ route('profile.shop', $order->seller->username) }}">{{ $order->product->name }}</a>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $order->email }}
                                </td>
                                <td class="px-6 py-4">
                                    {!! nl2br(e($order->details)) !!}
                                </td>
                                <td class="px-1 py-4 ">
                                    @if ($order->isCompletePending())
                                        Have you received your product?
                                        <form class="inline" action="{{ route('order.confirm') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="order_id" value={{ $order->id }}>
                                            <button>Yes</button>
                                        </form> / <form class="inline" action="{{ route('order.reject') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="order_id" value={{ $order->id }}>
                                            <button>No</button>
                                        </form>
                                    @elseif ($order->isCompleteRejected())
                                        Rejected Complete / Pending
                                    @elseif ($order->status == 'in_process')
                                        Working on it
                                    @else
                                        {{ ucwords($order->status) }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-lg text-neutral-400 dark:text-neutral-500">(None)</p>
            @endif
        </div>
    </x-page-panel>
</x-main-layout>
