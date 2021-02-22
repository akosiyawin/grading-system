@extends('layouts.registrar')

@push('styles')

@endpush

@section('content')
    <div class="jumbotron">
        <blockquote class="bg-transparent">
            <h1 class="display-4 text-dark">ANNOUNCEMENT PAGE</h1>
        </blockquote>
        <p class="lead">This page will let you encode and manage a certain students.</p>
        <hr class="my-4">
        <p>Normally students are batch encoded using a CSV File, but you can also add a student manually.</p>
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
    <registrar-announcement></registrar-announcement>
@endsection

@push('scripts')

@endpush