<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="https://www.epcst.edu.ph/images/epcst_logo.png">

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

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item">
                <b class="navbar-text">{{$yearTitle ?? "(NOTICE) SEMESTER IS NOT APPLIED"}}</b>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
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
        <a href="{{route('handler')}}" class="brand-link text-center">
            <span class="brand-text font-weight-bold">EPCST GRADE BOOK <i class="fas fa-book-open"></i></span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="https://www.epcst.edu.ph/images/epcst_logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                         style="opacity: .8">
                </div>
                <div class="info">
                    <a href="#" class="d-block">Hi, {{auth()->user()->first_name}}</a>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    @if(\App\Models\Semester::where('status',1)->exists())
                        <li class="nav-item">
                            <a href="{{route('registrar.index')}}"
                               class="nav-link  {{Route::is('registrar.index') ? 'active' : ''}}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('registrar.announcement')}}"
                               class="nav-link {{Route::is('registrar.announcement') ? 'active' : ''}}">
                                <i class="nav-icon fas fa-scroll"></i>
                                <p>
                                    Announcement
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('registrar.subject')}}"
                               class="nav-link  {{Route::is('registrar.subject') ? 'active' : ''}}">
                                <i class="nav-icon fas fa-swatchbook"></i>
                                <p>
                                    Subjects
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('registrar.studentView')}}"
                               class="nav-link {{Route::is('registrar.studentView') ? 'active' : ''}}">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    Students
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('registrar.teacherView')}}"
                               class="nav-link {{Route::is('registrar.teacherView') ? 'active' : ''}}">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                <p>
                                    Teachers
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('registrar.setup')}}"
                               class="nav-link {{Route::is('registrar.setup') ? 'active' : ''}}">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>
                                    Setup
                                </p>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{route('registrar.yearSemesterView')}}"
                           class="nav-link {{Route::is('registrar.yearSemesterView') ? 'active' : ''}}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                                Year | Semester
                            </p>
                        </a>
                    </li>
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
                <loading />
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
