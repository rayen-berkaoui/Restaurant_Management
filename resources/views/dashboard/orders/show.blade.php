@extends('layouts.dashboard')

@section('content')

<div class="bg-white p-6 rounded-xl shadow">

    <div class="mb-6">

        <h1 class="text-3xl font-bold">

            Order #{{ $order->id }}

        </h1>

        <p class="text-gray-500 mt-2">

            Table {{ $order->table->table_number }}

        </p>

    </div>

    <!-- Status -->
    <div class="mb-6">

        <span class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg">

            {{ ucfirst($order->status) }}

        </span>

    </div>

    <!-- Items -->
    <div class="space-y-4">

        @foreach($order->items as $item)

        <div class="flex justify-between border-b pb-4">

            <div>

                <h2 class="font-semibold text-lg">

                    {{ $item->menuItem->name }}

                </h2>

                <p class="text-gray-500">

                    Quantity: {{ $item->quantity }}

                </p>

            </div>

            <div class="font-bold">

                {{ $item->price }} DT

            </div>

        </div>

        @endforeach

    </div>
<div class="flex gap-3 mb-8">

    <form
        action="{{ route('orders.update', $order->id) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        <input
            type="hidden"
            name="status"
            value="preparing"
        >

        <button
            class="bg-blue-500 text-white px-4 py-2 rounded-lg"
        >
            Preparing
        </button>

    </form>

    <form
        action="{{ route('orders.update', $order->id) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        <input
            type="hidden"
            name="status"
            value="completed"
        >

        <button
            class="bg-green-600 text-white px-4 py-2 rounded-lg"
        >
            Completed
        </button>

    </form>

    <form
        action="{{ route('orders.update', $order->id) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        <input
            type="hidden"
            name="status"
            value="cancelled"
        >

        <button
            class="bg-red-500 text-white px-4 py-2 rounded-lg"
        >
            Cancel
        </button>

    </form>

</div>







    <!-- Total -->
    <div class="mt-8 flex justify-between text-2xl font-bold">

        <span>Total</span>

        <span>{{ $order->total_price }} DT</span>

    </div>

</div>

@endsection
