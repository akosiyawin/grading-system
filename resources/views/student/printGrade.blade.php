@extends('layouts.app')

@push('styles')
    <style>
        body {
            background-color: #efefef;
        }

        ul li {
            list-style: none;
            font-weight: 600;
            margin: 4px 0;
        }

        .list-title {
            font-weight: bold;
        }

        @media print {

            html, body {
                height: 100vh;
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden;
            }

        }
    </style>
@endpush

@section('content')
    <v-fab-transition>
        <v-btn
                absolute
                class="v-btn--example mt-5 bg-success"
                dark
                fab
                onClick="printNow()"
                right
                x-small
                top
        >
            <v-icon>mdi-printer-pos</v-icon>
        </v-btn>
    </v-fab-transition>
    <v-fab-transition>
        <v-btn
                absolute
                class="v-btn--example mt-5 bg-danger"
                dark
                fab
                href="/handler"
                right
                style="top: 30px"
                x-small
                top
        >
            <v-icon>mdi-arrow-left-bold</v-icon>
        </v-btn>
    </v-fab-transition>
    <print-grade :data="{{$data}}" :user="{{$info}}" :grade="{{$totalGrade}}" :units="{{$totalUnits}}"></print-grade>
@endsection

@push('scripts')
    <script>
        function printNow()
        {
            const insertScript = ` <script src="{{ asset('js/app.js') }}"><\/script>
            <script src="{{ asset('assets/adminlte/adminlte.min.js') }}"><\/script>`

            const currentWindow = window.open('', 'PRINT', 'height=720,width=980');
            currentWindow.document.write('<html><head><title>' + document.title + '</title>');
            currentWindow.document.write(`
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{asset('assets/adminlte/adminlte.min.css')}}">
    <style>
        /*Overrides bootstrap, removing orientation layout upon printing*/
        @page {
            size: auto;
             margin: 0mm;
        }
        ul li{
            list-style: none;
            font-weight: 600;
            margin: 4px 0;
        }
        .list-title{
            font-weight: bold;
        }
       @media print {
            html, body {
                height:100vh;
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden;
            }

        }
        #marginize{
            margin: 0 50px;
        }
    </style>
   ${insertScript}

    <\/head><body>`);

            currentWindow.document.write(document.querySelector('#printable').innerHTML);
            currentWindow.document.write('<\/body><\/html>');
            currentWindow.document.close();
            currentWindow.print();
            return true;
        }
    </script>
@endpush