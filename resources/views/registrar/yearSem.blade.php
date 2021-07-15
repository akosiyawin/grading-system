@extends('layouts.registrar')

@section('content')
    <jumbotron  title="MANAGE SCHOOL YEAR"
                subtitle="This is the main page which allows you to initialize and control the school year and semester."
                note="<b>*Notes:</b> Semesters and Subjects are dependent on school year. <br>
                Each subjects are created under current school year and semester. <br>
                They are loaded in the application dynamically."
    ></jumbotron>
   <registrar-yearsem></registrar-yearsem>
@endsection
