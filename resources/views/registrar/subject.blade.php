@extends('layouts.registrar')


@section('content')
    <div class="jumbotron">
        <blockquote class="bg-transparent">
            <h1 class="display-4 text-dark">CREATE SUBJECTS</h1>
        </blockquote>
        <p class="lead">This is the main page where you can <b>create</b> and <b>assign</b> subjects.</p>
        <hr class="my-4">
        <p>Each subjects that are created are assignable to certain teachers.</p>
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
    <registrar-subject></registrar-subject>
@endsection
