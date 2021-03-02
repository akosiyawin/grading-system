@extends('layouts.registrar')


@section('content')
    <jumbotron  title="CREATE SUBJECTS"
                subtitle="This is the main page where you can <b>create</b> and <b>assign</b> subjects."
                note="Each subjects that are created are assignable to certain teachers."
    ></jumbotron>
    <registrar-subject></registrar-subject>
@endsection
