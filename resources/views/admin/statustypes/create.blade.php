@extends('admin.master')

@section('pageTitle', 'Create Status Type')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Status Type</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.status-types.index') }}">Status Type</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <status-types-view
            :action-types="{{ $actionTypes }}"
            submit-url="{{ route('admin.status-types.store') }}"
        ></status-types-view>
    </section>
</div>

@endsection