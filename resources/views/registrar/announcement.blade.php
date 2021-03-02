@extends('layouts.registrar')

@push('styles')

@endpush

@section('content')
    <jumbotron  title="ANNOUNCEMENT PAGE"
                subtitle="This page will help you reach out to all students"
                note="All announcements will appear on every student accounts."
                ></jumbotron>
    <registrar-announcement></registrar-announcement>
@endsection

@push('scripts')

@endpush