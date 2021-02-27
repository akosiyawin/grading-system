@extends('layouts.registrar')

@push('styles')

@endpush

@section('content')
    <jumbotron  title="MANAGE TEACHERS"
                subtitle="This is the perfect section to <b>create</b> and <b>manage</b> teachers and their corresponding subjects."
                note="Please fill out the form carefully, make sure to not leave blank fields."
    ></jumbotron>
    <div class="row mb-5 d-flex justify-content-center">
        <div class="col-lg-5">
            <teacher-create />
        </div>
    </div>
    <teacher-table/>
@endsection

@push('scripts')

@endpush