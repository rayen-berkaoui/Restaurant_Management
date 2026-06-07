@extends('layouts.dashboard')

@section('content')

<div class="bg-white p-10 rounded-xl shadow max-w-xl text-center">

    <h1 class="text-3xl font-bold mb-6">

        Table {{ $table->table_number }}

    </h1>

    <div class="flex justify-center mb-6">

{!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)->generate(
    url('/menu/' . $table->qr_token)
) !!}

    </div>

    <p class="text-gray-600">

        Scan this QR code to open the menu

    </p>

</div>

@endsection