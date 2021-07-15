@extends('layouts.registrar')

@push('styles')

@endpush

@section('content')
    <jumbotron  title="MANAGE STUDENTS"
                subtitle="This page will let you encode and manage a certain students."
                note="Normally students are batch encoded using a CSV File, but you can also add a student manually."
    ></jumbotron>
    <registrar-student></registrar-student>
@endsection

@push('scripts')

@endpush