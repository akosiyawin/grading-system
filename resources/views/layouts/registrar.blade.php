<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    {{-- <link rel="dns-prefetch" href="//fonts.gstatic.com">
     <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">--}}

    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">

    <!-- Styles -->
    @stack('before-styles')
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"
          integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w=="
          crossorigin="anonymous"/>
    <link rel="stylesheet" href="{{asset('assets/adminlte/adminlte.min.css')}}">
    @stack('loader')

    @stack('styles')

</head>

<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
                @if (Route::has('login'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                @endif

                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a href="{{route('changePassword')}}" class="dropdown-item">
                            Change Password
                        </a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index3.html" class="brand-link">
            <!-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                 style="opacity: .8"> -->
            <span class="brand-text font-weight-light">EPCST WEB APPLICATION</span>
        </a>
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <!-- <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> -->
                </div>
                <div class="info">
                    <a href="#" class="d-block">EPCST Registrar</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <a href="{{route('registrar.yearSemesterView')}}"
                           class="nav-link {{Route::is('registrar.yearSemesterView') ? 'active' : ''}}">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Year | Semester
                            </p>
                        </a>
                    </li>
                    @if(\App\Models\Semester::where('status',1)->exists())
                       {{-- <li class="nav-item">
                            <a href="{{route('registrar.index')}}"
                               class="nav-link  {{Route::is('registrar.index') ? 'active' : ''}}">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>--}}
                        <li class="nav-item">
                            <a href="{{route('registrar.subject')}}"
                               class="nav-link  {{Route::is('registrar.subject') ? 'active' : ''}}">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Subjects
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('registrar.studentView')}}"
                               class="nav-link {{Route::is('registrar.studentView') ? 'active' : ''}}">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Students
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('registrar.teacherView')}}"
                               class="nav-link {{Route::is('registrar.teacherView') ? 'active' : ''}}">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Teachers
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('registrar.setup')}}"
                               class="nav-link {{Route::is('registrar.setup') ? 'active' : ''}}">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Setup
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('registrar.announcement')}}"
                               class="nav-link {{Route::is('registrar.announcement') ? 'active' : ''}}">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Announcement
                                </p>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <div class="content-wrapper">
        <section class="content">
            <div id="app">
                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2021-2022 <a href="#">EPCST</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 0.0.1
        </div>
    </footer>
</div>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('assets/adminlte/adminlte.min.js') }}"></script>
@stack('scripts')
</body>
</html>
