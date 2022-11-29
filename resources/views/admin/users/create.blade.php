@extends('admin.master')

@section('pageTitle', 'Create Patient or Secretary')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Patient or Secretary</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Patient or Secretary</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <users-view
        :generate-password="{{ true }}"
        fetch-url="{{ route('admin.users.fetch-item') }}"
        submit-url="{{ route('admin.users.store') }}"
        ></users-view>
    </section>
</div>

@endsection