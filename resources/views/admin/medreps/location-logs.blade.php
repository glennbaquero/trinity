@extends('admin.master')

@section('pageTitle', 'Medical Representatives Location Logs')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Medical Representatives Location Logs</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Medical Representatives Location Logs</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="col-xs-12">
            <div class="card">
              <medreps-location-logs
              :logs="{{ $logs }}"
              ></medreps-location-logs>  
            </div>
        </div>
    </section>
</div>

@endsection