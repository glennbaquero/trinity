@extends('admin.master')

@section('pageTitle', 'Dashboard')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Dashboard</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
            
        <dashboard-analytics 
        class="row"
        fetch-url="{{ route('admin.analytics.fetch.dashboard') }}"
        logs-index-url="{{ route('admin.activity-logs.index') }}"
        logs-url="{{ route('admin.activity-logs.recent') }}"
        sales-url="{{ route('admin.sales') }}"
        inventory-url="{{ route('admin.inventories.index') }}"
        ></dashboard-analytics>
    
    </section>
</div>

@endsection