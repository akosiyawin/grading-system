@extends('layouts.registrar')

@push('styles')

@endpush

@section('content')
    <div class="jumbotron">
        <blockquote class="bg-transparent">
            <h1 class="display-4 text-dark">SYSTEM SETUP</h1>
        </blockquote>
        <p class="lead">This page is responsible for managing the basic setup of your system.</p>
        <hr class="my-4">
        <p><b>*Note:</b> This should be the first page you need to configure.</p>
        <p class="lead">
            <v-btn
                    rounded
                    class="bg-primary"
                    dark
            >
                Go to Dashboard
            </v-btn>
        </p>
    </div>

    <!--Row-->
    <registrar-courses></registrar-courses>
    <!--Row-->
    <registrar-department></registrar-department>

    <dialog-message />

@endsection
