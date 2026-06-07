@extends('layouts.dashboard')

@section('content')

<div class="bg-white p-6 rounded-xl shadow max-w-xl">

    <h1 class="text-2xl font-bold mb-6">
        Add Table
    </h1>

    <form
        action="{{ route('tables.store') }}"
        method="POST"
    >

        @csrf

        <div class="mb-4">

            <label class="block mb-2 font-medium">
                Table Number
            </label>

            <input
                type="number"
                name="table_number"
                class="w-full border rounded-lg px-4 py-2"
                required
            >

        </div>

        <button
            type="submit"
            class="bg-black text-white px-6 py-2 rounded-lg"
        >
            Save Table
        </button>

    </form>

</div>

@endsection