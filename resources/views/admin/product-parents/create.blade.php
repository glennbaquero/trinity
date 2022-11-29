@extends('admin.master')

@section('pageTitle', 'Create Product Parent')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Product Parent</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.product-parents.index') }}">Product Parents</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <product-parent-view
        fetch-url="{{ route('admin.product-parents.fetch-item') }}"
        submit-url="{{ route('admin.product-parents.store') }}"
        ></product-parent-view>
    </section>
</div>

@endsection