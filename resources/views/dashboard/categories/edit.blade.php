@extends('layouts.dashboard')

@section('content')

<div class="bg-white p-6 rounded-xl shadow max-w-xl">

    <h1 class="text-2xl font-bold mb-6">
        Edit Category
    </h1>

    <form
        action="{{ route('categories.update', $category->id) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        <div class="mb-4">

            <label class="block mb-2 font-medium">
                Category Name
            </label>

            <input
                type="text"
                name="name"
                value="{{ $category->name }}"
                class="w-full border rounded-lg px-4 py-2"
                required
            >

        </div>

        <button
            type="submit"
            class="bg-black text-white px-6 py-2 rounded-lg"
        >
            Update
        </button>

    </form>

</div>

@endsection