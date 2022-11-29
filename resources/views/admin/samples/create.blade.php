@extends('admin.master')

@section('pageTitle', 'Create Sample Item')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Sample Item</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.sample-items.index') }}">Sample Items</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <sample-items-view
        fetch-url="{{ route('admin.sample-items.fetch-item') }}"
        submit-url="{{ route('admin.sample-items.store') }}"
        ></sample-items-view>
    </section>
</div>

@endsection