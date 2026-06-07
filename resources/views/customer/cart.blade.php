@extends('customer.layout')

@section('title')
    Cart
@endsection

@section('content')

<div class="max-w-3xl mx-auto p-5">

    <!-- Header -->
    <div class="mb-8">

        <h1 class="text-4xl font-bold">
            Your Cart
        </h1>

        <p class="text-gray-500 mt-2">
            Review your order before checkout
        </p>

    </div>

    <!-- Cart Container -->
    <div class="bg-white rounded-3xl shadow-lg p-6">

        @php
            $total = 0;
        @endphp

        @forelse($cart as $id => $item)

            @php
                $subtotal =
                    $item['price'] * $item['quantity'];

                $total += $subtotal;
            @endphp

            <!-- Item -->
<div class="flex justify-between items-center border-b py-5">

    <div>

        <h2 class="text-xl font-bold">

            {{ $item['name'] }}

        </h2>

        <div class="flex items-center gap-3 mt-3">

            <!-- Decrease -->
            <form
                action="{{ route('cart.decrease', $id) }}"
                method="POST"
            >

                @csrf

                <button
                    class="bg-gray-200 w-8 h-8 rounded-full"
                >
                    -
                </button>

            </form>

            <!-- Quantity -->
            <span class="font-semibold">

                {{ $item['quantity'] }}

            </span>

            <!-- Increase -->
            <form
                action="{{ route('cart.increase', $id) }}"
                method="POST"
            >

                @csrf

                <button
                    class="bg-black text-white w-8 h-8 rounded-full"
                >
                    +
                </button>

            </form>

        </div>

    </div>

    <div class="text-right">

        <p class="font-bold text-lg">

            {{ $subtotal }} DT

        </p>

        <!-- Remove -->
        <form
            action="{{ route('cart.remove', $id) }}"
            method="POST"
            class="mt-3"
        >

            @csrf
            @method('DELETE')

            <button
                class="text-red-500 text-sm"
            >
                Remove
            </button>

        </form>

    </div>

</div>
@empty
            <div class="text-center py-12">

                <p class="text-gray-500 text-lg">

                    Your cart is empty

                </p>

            </div>

        @endforelse

        <!-- Total -->
        <div class="flex justify-between items-center mt-8">

            <h2 class="text-2xl font-bold">

                Total

            </h2>

            <span class="text-2xl font-bold">

                {{ $total }} DT

            </span>

        </div>

        <!-- Checkout -->
        <form
            action="{{ route('orders.place') }}"
            method="POST"
            class="mt-8"
        >

            @csrf

            <button
                type="submit"
                class="w-full bg-black hover:bg-gray-800 text-white py-4 rounded-2xl text-lg font-semibold transition"
            >
                Place Order
            </button>

        </form>

    </div>

</div>

@endsection
