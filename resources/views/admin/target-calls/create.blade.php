@extends('admin.master')

@section('pageTitle', 'Create Target Call')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Target Call</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.target-calls.index') }}">Target Call</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <target-calls-view
        fetch-url="{{ route('admin.target-calls.fetch-item') }}"
        submit-url="{{ route('admin.target-calls.store') }}"
        :months="{{ $months }}"
        year="{{ date('Y') }}"
        :medreps="{{ $medreps }}"
        ></target-calls-view>
    </section>
</div>

@endsection