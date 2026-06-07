@extends('layouts.dashboard')

@section('content')

<div class="bg-white p-6 rounded-xl shadow">

    <div class="flex justify-between items-center mb-6">

        <h1 class="text-2xl font-bold">
            Orders
        </h1>

    </div>

    <table class="w-full">

        <thead>

            <tr class="border-b text-left">

                <th class="py-3">Table</th>

                <th>Status</th>

                <th>Total</th>

                <th>Created</th>

                <th>Actions</th>

            </tr>

        </thead>

        <tbody>

            @foreach($orders as $order)

            <tr class="border-b">

                <td class="py-4">

                    Table {{ $order->table->table_number }}

                </td>

                <td>

                    <span class="px-3 py-1 rounded-lg bg-yellow-100 text-yellow-700">

                        {{ ucfirst($order->status) }}

                    </span>

                </td>

                <td>

                    {{ $order->total_price }} DT

                </td>

                <td>

                    {{ $order->created_at->format('H:i') }}

                </td>

                <td>

                    <a
                        href="{{ route('orders.show', $order->id) }}"
                        class="bg-black text-white px-4 py-1 rounded-lg"
                    >
                        View
                    </a>

                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection
