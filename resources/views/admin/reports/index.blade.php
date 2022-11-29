@extends('admin.master')

@section('pageTitle', 'Reports')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Reports</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Reports</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-body">
                    <reports
                    post-url="{{ route('admin.reports.generate') }}"
                    fetch-url="{{ route('admin.consultations.fetch-item') }}"
                    >
                    </reports>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection