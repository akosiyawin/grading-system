@extends('layouts.registrar')

@push('styles')

@endpush

@section('content')
    <jumbotron  title="SYSTEM SETUP"
                subtitle="This page is responsible for managing the basic setup of your system."
                note="<b>*Note:</b> This should be the first page you need to configure."
    ></jumbotron>

    <!--Row-->
    <registrar-courses></registrar-courses>
    <!--Row-->
    <registrar-department></registrar-department>

    <dialog-message />
@endsection
