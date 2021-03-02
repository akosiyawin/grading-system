@extends('layouts.app')

@push('styles')
    <style>
        body{
            background-image: url('https://epcst.files.wordpress.com/2012/08/eastwoods-3.jpg');
            background-position: top;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

    </style>
@endpush

@section('content')
   <Login />
@endsection

@push('scripts')
    <script>
        // alert(1)
    </script>
@endpush