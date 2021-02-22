@extends('layouts.registrar')

@section('content')
    <div class="jumbotron">
        <blockquote class="bg-transparent">
            <h1 class="display-4  text-dark">Define School Year | Semester</h1>
        </blockquote>
        <p class="lead">This is the main page which allows you to initialize and control the school year and
            semester.</p>
        <hr class="my-4">
        <p><b>*Notes:</b> Semesters and Subjects are dependent on school year. <br>
            Each subjects are created under current school year and semester. <br>
            They are loaded in the application dynamically.
        </p>
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
   <registrar-yearsem></registrar-yearsem>
@endsection
