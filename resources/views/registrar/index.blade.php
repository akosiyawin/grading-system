@extends('layouts.registrar')

@push('styles')

@endpush

@section('content')
    <jumbotron  title="DASHBOARD MENU"
                note="This is only the summarize of your data."
                subtitle="Welcome to your main home, In this page you can view the overall records of your system."
                :dashboard-btn="false"></jumbotron>
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{$students}}</h3>

                    <p>How many students?</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{$teachers}}</h3>
                    <p>How many teachers?</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{$registrars}}</h3>
                    <p>How many registrars?</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{$subjects}}</h3>
                    <p>How many subjects?</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
    </div>

    <div class="row">
        <div class="col-sm-6 col-12">
            <div class="row">
                <div class="col-sm-6 col-12">
                    <div class="description-block border-right">
                        <h5 class="description-header">{{$courses}}</h5>
                        <span class="description-text">COURSES</span>
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <div class="description-block border-right">
                        <h5 class="description-header">{{$departments}}</h5>
                        <span class="description-text">DEPARTMENTS</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                        <p class="text-success text-xl">
                            <i class="ion ion-ios-refresh-empty"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                    <span class="font-weight-bold">
                      <i class="ion ion-android-arrow-up text-success"></i> {{$approvals}}
                    </span>
                            <span class="text-muted">GRADES FOR APPROVAL</span>
                        </p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                        <p class="text-success text-xl">
                            <i class="ion ion-ios-refresh-empty"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                    <span class="font-weight-bold">
                      <i class="ion ion-android-arrow-up text-success"></i> {{$approved}}
                    </span>
                            <span class="text-muted">APPROVED GRADES</span>
                        </p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                        <p class="text-warning text-xl">
                            <i class="ion ion-ios-cart-outline"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                    <span class="font-weight-bold">
                      <i class="ion ion-android-arrow-up text-warning"></i>{{$submissions}}
                    </span>
                            <span class="text-muted">SUBMITTED GRADES</span>
                        </p>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-0">
                        <p class="text-danger text-xl">
                            <i class="ion ion-ios-people-outline"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                    <span class="font-weight-bold">
                      <i class="ion ion-android-arrow-down text-danger"></i> {{$suspended}}
                    </span>
                            <span class="text-muted">COUNT OF SUSPENDED USERS</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush