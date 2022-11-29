@extends('admin.master')

@section('pageTitle', 'Create a Doctor')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create a Doctor</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.doctors.index') }}">Doctors</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <doctors-view
        fetch-url="{{ route('admin.doctors.fetch-item') }}"
        submit-url="{{ route('admin.doctors.store') }}"
        create-mode="{{ request()->is('admin/doctors/create') }}"
        ></doctors-view>
    </section>
</div>

@endsection