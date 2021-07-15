@extends('layouts.teacher')

@section('content')
    <div class="jumbotron">
        <h1 class="display-4">Designate Students!</h1>
        <p class="lead">This is the page where you designate students under a certain subjects.</p>
        <hr class="my-4">
        <p>Please choose carefully, designated students will automatically categorized as enrolled to your chosen
            subject.</p>
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


    <teacher-student></teacher-student>

@endsection

@push('scripts')

@endpush

