@extends('admin.master')

@section('pageTitle', 'Create Shipping Matrix')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Shipping Matrix</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.shipping-matrixes.index') }}">Shipping Matrixes</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <shipping-matrix-view
        fetch-url="{{ route('admin.shipping-matrixes.fetch-item') }}"
        submit-url="{{ route('admin.shipping-matrixes.store') }}"
        ></shipping-matrix-view>
    </section>
</div>

@endsection