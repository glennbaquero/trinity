@extends('web.master-dashboard')

@section('pageTitle', 'Activity Logs')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Activity Logs</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Activity Logs</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-body">
                <activity-logs-table 
                fetch-url="{{ route('web.activity-logs.fetch') }}"
                actionable
                hide-causer
                show-subject
                ></activity-logs-table>
            </div>
        </div>
    </section>
</div>

@endsection