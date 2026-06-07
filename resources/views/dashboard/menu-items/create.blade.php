@extends('layouts.dashboard')

@section('content')

<div class="bg-white p-6 rounded-xl shadow max-w-2xl">

    <h1 class="text-2xl font-bold mb-6">
        Add Menu Item
    </h1>

    <form
    action="{{ route('menu-items.store') }}"
    method="POST"
    enctype="multipart/form-data"
        >

        @csrf

        <!-- Name -->
        <div class="mb-4">

            <label class="block mb-2 font-medium">
                Item Name
            </label>

            <input
                type="text"
                name="name"
                class="w-full border rounded-lg px-4 py-2"
                required
            >

        </div>

        <!-- Category -->
        <div class="mb-4">

            <label class="block mb-2 font-medium">
                Category
            </label>

            <select
                name="menu_category_id"
                class="w-full border rounded-lg px-4 py-2"
                required
            >

                @foreach($categories as $category)

                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>

                @endforeach

            </select>

        </div>

        <!-- Description -->
        <div class="mb-4">

            <label class="block mb-2 font-medium">
                Description
            </label>

            <textarea
                name="description"
                class="w-full border rounded-lg px-4 py-2"
            ></textarea>

        </div>

        <!-- Price -->
        <div class="mb-4">

            <label class="block mb-2 font-medium">
                Price
            </label>

            <input
                type="number"
                step="0.01"
                name="price"
                class="w-full border rounded-lg px-4 py-2"
                required
            >

        </div>

        <!-- Available -->
        <div class="mb-6 flex items-center gap-2">

            <input
                type="checkbox"
                name="available"
                value="1"
                checked
            >

            <label>
                Available
            </label>

        </div>
<div class="mb-5">

    <label class="block mb-2 font-medium">
        Product Image
    </label>

    <input
        type="file"
        name="image"
        class="w-full border rounded-xl px-4 py-3"
    >

</div>
        <button
            type="submit"
            class="bg-black text-white px-6 py-2 rounded-lg"
        >
            Save Item
        </button>

    </form>

</div>

@endsection