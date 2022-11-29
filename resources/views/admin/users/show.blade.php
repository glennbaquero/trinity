@extends('admin.master')

@section('pageTitle', $item->renderName())

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $item->renderName() }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ $item->renderName() }}</a></li>
                </ol>
            </div>
        </div>
    </section>

    <page-pagination fetch-url="{{ route('admin.users.fetch-pagination', $item->id) }}"></page-pagination>

    <section class="content">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#tab1" data-toggle="tab">Information</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab2" data-toggle="tab">Vouchers</a></li>
                    <li class="nav-item"><a @click="initList('table-1')" class="nav-link" href="#tab2" data-toggle="tab">Activity Logs</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane show active" id="tab1">
                       <users-view
                       fetch-url="{{ route('admin.users.fetch-item', $item->id) }}"
                       submit-url="{{ route('admin.users.update', $item->id) }}"
                       ></users-view>
                    </div>
                    <div class="tab-pane show" id="tab2">
                       <user-vouchers-table
                       fetch-url="{{ route('admin.user-vouchers.fetch', $item->id) }}"
                       ></user-vouchers-table>
                    </div>
                    <div class="tab-pane" id="tab3">
                        <activity-logs-table 
                        ref="table-1"
                        :auto-fetch="false"
                        fetch-url="{{ route('admin.activity-logs.fetch.users', $item->id) }}"
                        ></activity-logs-table>
                    </div>
                </div>
            </div>
        </div>
        @if(!$item->isPatient())
            <div class="card">
                <div class="card-body">
                    <invoices-table
                    :statuses="{{ $statuses }}"
                    :doctors="{{ $item->doctors }}"
                    fetch-url="{{ route('admin.invoices.fetch-by-secretary', $item->id) }}"
                    submit-url="{{ route('admin.invoices.update') }}"
                    export-url="{{ route('admin.invoices.export') }}"
                    ></invoices-table>                    
                </div>          
            </div>
        @endif
    </section>
</div>

@endsection