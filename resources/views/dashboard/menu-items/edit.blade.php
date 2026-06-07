@extends('layouts.dashboard')

@section('content')

<div class="bg-white p-6 rounded-xl shadow max-w-2xl">

    <h1 class="text-2xl font-bold mb-6">
        Edit Menu Item
    </h1>

    <form
    action="{{ route('menu-items.update', $item->id) }}"
    method="POST"
    enctype="multipart/form-data"
>

        @csrf
        @method('PUT')

        <!-- Name -->
        <div class="mb-4">

            <label class="block mb-2 font-medium">
                Item Name
            </label>

            <input
                type="text"
                name="name"
                value="{{ $item->name }}"
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
            >

                @foreach($categories as $category)

                    <option
                        value="{{ $category->id }}"
                        @if($item->menu_category_id == $category->id) selected @endif
                    >
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
            >{{ $item->description }}</textarea>

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
                value="{{ $item->price }}"
                class="w-full border rounded-lg px-4 py-2"
            >

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
@if($item->image)

    <div class="mt-4">

        <img
            src="{{ asset('storage/' . $item->image) }}"
            class="w-40 h-40 object-cover rounded-2xl shadow"
        >

    </div>

@endif
</div>
        <!-- Available -->
        <div class="mb-6 flex items-center gap-2">

            <input
                type="checkbox"
                name="available"
                value="1"
                @if($item->available) checked @endif
            >

            <label>
                Available
            </label>

        </div>

        <button
            type="submit"
            class="bg-black text-white px-6 py-2 rounded-lg"
        >
            Update Item
        </button>

    </form>

</div>

@endsection