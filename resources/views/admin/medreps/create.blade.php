@extends('admin.master')

@section('pageTitle', 'Create Medical Representative')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Medical Representative</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.medreps.index') }}">Medical Representatives</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <medreps-view
        fetch-url="{{ route('admin.medreps.fetch-item') }}"
        submit-url="{{ route('admin.medreps.store') }}"
        ></medreps-view>
    </section>
</div>

@endsection