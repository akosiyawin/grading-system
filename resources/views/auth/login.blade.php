@extends('layouts.app')

@section('content')
    <div class="content-container wrap">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Login') }}</div>
                        <div class="card-body">
                            <Login />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function onLoginAttempt(event){
            event.preventDefault();
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            axios.post('')
        }
    </script>
@endpush