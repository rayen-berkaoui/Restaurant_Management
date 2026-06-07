@extends('customer.layout')

@section('title')
    Order Status
@endsection

@section('content')

<div class="max-w-3xl mx-auto p-5">

    <!-- Success -->
    <div class="bg-white rounded-3xl shadow-lg p-8">

        <div class="text-center mb-10">

            <div class="text-6xl mb-4">
                ✅
            </div>

            <h1 class="text-4xl font-bold">

                Order Confirmed

            </h1>

            <p class="text-gray-500 mt-3">

                Your order has been sent successfully

            </p>

        </div>

        <!-- Order Info -->
        <div class="border rounded-2xl p-5 mb-8">

            <div class="flex justify-between mb-4">

                <span class="font-semibold">
                    Order ID
                </span>

                <span>
                    #{{ $order->id }}
                </span>

            </div>

            <div class="flex justify-between">

                <span class="font-semibold">
                    Status
                </span>

                <span
                    class="px-4 py-1 rounded-full text-sm font-semibold

                    @if($order->status == 'pending')
                        bg-yellow-100 text-yellow-700
                    @elseif($order->status == 'preparing')
                        bg-blue-100 text-blue-700
                    @elseif($order->status == 'completed')
                        bg-green-100 text-green-700
                    @else
                        bg-red-100 text-red-700
                    @endif
                    "
                >
                    {{ ucfirst($order->status) }}
                </span>

            </div>

        </div>

        <!-- Items -->
        <div class="space-y-4 mb-8">

            @foreach($order->items as $item)

                <div class="flex justify-between border-b pb-4">

                    <div>

                        <h2 class="font-bold text-lg">

                            {{ $item->menuItem->name }}

                        </h2>

                        <p class="text-gray-500">

                            Quantity:
                            {{ $item->quantity }}

                        </p>

                    </div>

                    <div class="font-bold">

                        {{ $item->price * $item->quantity }} DT

                    </div>

                </div>

            @endforeach

        </div>

        <!-- Total -->
        <div class="flex justify-between items-center text-2xl font-bold">

            <span>Total</span>

            <span>

                {{ $order->total_price }} DT

            </span>

        </div>

    </div>

</div>

@endsection
