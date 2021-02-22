@extends('layouts.registrar')

@push('styles')

@endpush

@section('content')
{{--    <pre>
        {{\App\Models\Teacher::all()}}
    </pre>
    <pre>
        {{\App\Models\User::where('role_id',2)->get()}}
    </pre>--}}
    <div class="jumbotron">
        <blockquote class="bg-transparent">
            <h1 class="display-4 text-dark">MANAGE TEACHERS</h1>
        </blockquote>
        <p class="lead">This is the perfect section to <b>create</b> and <b>manage</b> teachers and their corresponding subjects.</p>
        <hr class="my-4">
        <p>Please fill out the form carefully, make sure to not leave blank fields.</p>
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
    <div class="row mb-5 d-flex justify-content-center">
        <div class="col-lg-5">
            <teacher-create />
        </div>
    </div>
    <teacher-table/>
@endsection

@push('scripts')

@endpush