@extends('layouts.dashboard')

@section('content')

<div class="bg-white p-6 rounded-xl shadow">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">

        <h1 class="text-2xl font-bold">
            Tables
        </h1>

        <a
            href="{{ route('tables.create') }}"
            class="bg-black text-white px-4 py-2 rounded-lg"
        >
            Add Table
        </a>

    </div>

    <!-- Tables -->
    <table class="w-full">

        <thead>

            <tr class="border-b text-left">

                <th class="py-3">
                    Table Number
                </th>

                <th>
                    QR Token
                </th>

                <th>
                    Actions
                </th>

            </tr>

        </thead>

        <tbody>

            @foreach($tables as $table)

            <tr class="border-b">

                <!-- Table Number -->
                <td class="py-4">
                    Table {{ $table->table_number }}
                </td>

                <!-- QR Token -->
                <td>
                    {{ $table->qr_token }}
                </td>

                <!-- Actions -->
                <td class="py-4">

                    <div class="flex items-center gap-2">

                        <!-- QR Button -->
                        <a
                            href="{{ route('tables.qr', $table->id) }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-1 rounded-lg font-medium"
                        >
                            QR
                        </a>

                        <!-- Edit Button -->
                        <a
                            href="{{ route('tables.edit', $table->id) }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded-lg"
                        >
                            Edit
                        </a>

                        <!-- Delete Button -->
                        <form
                            action="{{ route('tables.destroy', $table->id) }}"
                            method="POST"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-lg"
                            >
                                Delete
                            </button>

                        </form>

                    </div>

                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection