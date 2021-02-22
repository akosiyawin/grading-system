@extends('layouts.registrar')

@push('styles')

@endpush

@section('content')
    <div class="jumbotron">
        <blockquote class="bg-transparent">
            <h1 class="display-4 text-dark">ANNOUNCEMENT PAGE</h1>
        </blockquote>
        <p class="lead">This page will help you reach out to all students</p>
        <hr class="my-4">
        <p>Please be careful with your words.</p>
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