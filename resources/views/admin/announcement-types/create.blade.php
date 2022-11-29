@extends('admin.master')

@section('pageTitle', 'Create Announcement Type')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Announcement Type</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.announcement-types.index') }}">Announcement Type</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <announcement-types-view
        fetch-url="{{ route('admin.announcement-types.fetch-item') }}"
        submit-url="{{ route('admin.announcement-types.store') }}"
        ></announcement-types-view>
    </section>
</div>

@endsection