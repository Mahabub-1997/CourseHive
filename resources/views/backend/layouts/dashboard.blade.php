@extends('backend.partials.master')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Page Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">

                    <!-- Empty column (can be used for filters/search later) -->
                    <div class="col-md-12 align-items-center"></div>

                    <!-- Page Title -->
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div>

                    <!-- Breadcrumb (currently empty) -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right"></ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <!-- Header Info Box -->
                <div class="row">
                    <div class="col-12">
                        <div class="info-box mb-3 d-flex justify-content-between align-items-center bg-primary text-white">
                            <div class="info-box-content">
                                <span class="info-box-text fw-bold" style="font-size: 2rem;">My Course</span>
                                <span class="info-box-number" style="font-size: .7rem;">
                                    You're making excellent progress in your healthcare training
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info boxes row -->
                <div class="row">

                    <!-- Enrolled Courses -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="info-box mb-3 d-flex justify-content-between align-items-center">
                            <div class="info-box-content">
                                <span class="info-box-text fw-bold" style="font-size: 1.5rem;">Enroll Courses</span>
                                <span class="info-box-number text-primary" style="font-size: 2rem;">
                                    {{ $inProgress }}
                                </span>
                            </div>
                            <span class="info-box-icon bg-white elevation-1 text-dark">
                                <i class="fas fa-graduation-cap" style="font-size: 2rem;"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Completed Lessons -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="info-box mb-3 d-flex justify-content-between align-items-center">
                            <div class="info-box-content">
                                <span class="info-box-text fw-bold" style="font-size: 1.5rem;">Completed Lesson</span>
                                <span class="info-box-number text-primary" style="font-size: 2rem;">
                                    {{ $inComplete }}
                                </span>
                            </div>
                            <span class="info-box-icon bg-white elevation-1 text-dark">
                                <i class="fas fa-book-reader" style="font-size: 2rem;"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Certificates Earned -->
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="info-box mb-3 d-flex justify-content-between align-items-center">
                            <div class="info-box-content">
                                <span class="info-box-text fw-bold" style="font-size: 1.5rem;">Certificate</span>
                                <span class="info-box-number text-primary" style="font-size: 2rem;">
                                    {{ $enrollments->where('status', 'success')->count() }}
                                </span>
                            </div>
                            <span class="info-box-icon bg-white elevation-1 text-dark">
                                <i class="fas fa-certificate" style="font-size: 2rem;"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- /.row -->

                <!-- User Welcome Card -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">

                            <!-- Card Header -->
                            <div class="card-header">
                                <h5 class="card-title">{{ Auth()->user()->name }} - Dashboard</h5>
                                <div class="card-tools">
                                    <!-- Collapse Button -->
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>

                                    <!-- Tools Dropdown (future use) -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                                            <i class="fas fa-wrench"></i>
                                        </button>
                                    </div>

                                    <!-- Remove Card -->
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->

                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        Welcome To {{ Auth()->user()->name }} - Dashboard
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
