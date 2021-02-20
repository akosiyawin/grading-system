@extends('layouts.teacher')
{{--Dito na ko lilipat ko sa vue to --}}
@section('content')
    <div class="jumbotron">
        <h1 class="display-4">Designate Subjects!</h1>
        <p class="lead">This is the page where you can create a subject, designated by the Registrar.</p>
        <hr class="my-4">
        <p>Please choose carefully the subjects that you want to include on your class.</p>
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
    <teacher-subject></teacher-subject>
@endsection

@push('scripts')

@endpush

@push('styles')
    <style>
        .done .text{
            text-decoration: none !important;
            color: red !important;
        }
    </style>
@endpush
