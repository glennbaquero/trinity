@extends('admin.master')

@section('pageTitle', 'Create Standard Shipping Fee')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Standard Shipping Fee</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.shipping-standards.index') }}">Standard Shipping Fees</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <shipping-standards-view
        fetch-url="{{ route('admin.shipping-standards.fetch-item') }}"
        submit-url="{{ route('admin.shipping-standards.store') }}"
        :provinces="{{ $provinces }}"
        ></shipping-standards-view>
    </section>
</div>

@endsection