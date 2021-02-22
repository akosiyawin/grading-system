<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<<<<<<< HEAD

=======
>>>>>>> bf8422f7d4deb3d2e4cb2833bd0bab851ae14f2b
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="https://www.epcst.edu.ph/images/epcst_logo.png">


    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">

    <!-- Styles -->
    @stack('before-styles')
    <link rel="stylesheet" href="{{asset('css/app.css')}}">

    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">


<<<<<<< HEAD
    <div id="app">
        @yield('content')
        <loading />
    </div>


    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>
=======
<div id="app">
    @yield('content')
</div>


<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
>>>>>>> bf8422f7d4deb3d2e4cb2833bd0bab851ae14f2b
