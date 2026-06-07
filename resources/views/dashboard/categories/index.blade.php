@extends('layouts.dashboard')

@section('content')

<div class="bg-white p-6 rounded-xl shadow">

    <div class="flex justify-between items-center mb-6">

        <h1 class="text-2xl font-bold">
            Categories
        </h1>

        <a
            href="{{ route('categories.create') }}"
            class="bg-black text-white px-4 py-2 rounded-lg"
        >
            Add Category
        </a>

    </div>

    <table class="w-full">

        <thead>

            <tr class="border-b text-left">

                <th class="py-3">ID</th>

                <th>Name</th>

                <th class="py-3">Actions</th>

            </tr>

        </thead>

        <tbody>

            @foreach($categories as $category)

            <tr class="border-b">

                <td class="py-4">
                    {{ $category->id }}
                </td>

                <td>
                    {{ $category->name }}
                </td>

                <td class="py-4 flex gap-2">

                    <!-- Edit Button -->
                    <a
                        href="{{ route('categories.edit', $category->id) }}"
                        class="bg-blue-500 text-white px-3 py-1 rounded-lg"
                    >
                        Edit
                    </a>

                    <!-- Delete Button -->
                    <form
                        action="{{ route('categories.destroy', $category->id) }}"
                        method="POST"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="bg-red-500 text-white px-3 py-1 rounded-lg"
                        >
                            Delete
                        </button>

                    </form>

                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection