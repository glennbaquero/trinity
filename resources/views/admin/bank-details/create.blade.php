@extends('admin.master')

@section('pageTitle', 'Create Bank Detail')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Bank Detail</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.bank-details.index') }}">Bank Details</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <bank-details-view
        fetch-url="{{ route('admin.bank-details.fetch-item') }}"
        submit-url="{{ route('admin.bank-details.store') }}"
        ></bank-details-view>
    </section>
</div>

@endsection