@extends('customer.layout')

@section('title')
    {{ $restaurant->name }}
@endsection

@section('content')

<!-- Restaurant Header -->
<div class="bg-black text-white p-6 rounded-b-3xl shadow-lg">

    <div class="max-w-5xl mx-auto">

        <h1 class="text-4xl font-bold">
            {{ $restaurant->name }}
        </h1>

        <p class="mt-2 text-gray-300">

            Table {{ $table->table_number }}

        </p>

    </div>

</div>

<!-- Categories -->
<div class="max-w-5xl mx-auto p-5">

    @foreach($categories as $category)

        <div class="mb-10">

            <!-- Category Title -->
            <h2 class="text-2xl font-bold mb-5">

                {{ $category->name }}

            </h2>

            <!-- Items -->
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">

                @foreach($category->items as $item)

                    @if($item->available)

                    <div class="bg-white rounded-2xl shadow-md overflow-hidden">

                        <!-- Image Placeholder -->
@if($item->image)

<img
    src="{{ asset('storage/' . $item->image) }}"
    class="w-full h-48 object-cover"
>

@else

<div class="h-48 bg-gray-200 flex items-center justify-center text-gray-400">

    Food Image

</div>

@endif

                        <!-- Content -->
                        <div class="p-5">

                            <div class="flex justify-between mb-3">

                                <h3 class="text-xl font-bold">

                                    {{ $item->name }}

                                </h3>

                                <span class="font-bold">

                                    {{ $item->price }} DT

                                </span>

                            </div>

                            <p class="text-gray-500 text-sm mb-5">

                                {{ $item->description }}

                            </p>

                            <!-- Add To Cart -->
                            <form
                                action="{{ route('cart.add') }}"
                                method="POST"
                            >

                                @csrf

                                <input
                                    type="hidden"
                                    name="menu_item_id"
                                    value="{{ $item->id }}"
                                >

                                <button
                                    type="submit"
                                    class="w-full bg-black text-white py-3 rounded-xl hover:bg-gray-800"
                                >
                                    Add To Cart
                                </button>

                            </form>

                        </div>

                    </div>

                    @endif

                @endforeach

            </div>

        </div>

    @endforeach

</div>

<!-- Floating Cart -->
<a
    href="{{ route('cart.index') }}"
    class="fixed bottom-6 right-6 bg-black text-white px-6 py-4 rounded-full shadow-2xl"
>
    Cart
</a>

@endsection
