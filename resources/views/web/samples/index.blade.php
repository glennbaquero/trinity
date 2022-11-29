@extends('web.master-dashboard')

@section('pageTitle', 'Sample Items')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Sample Items</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Sample Items</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="mb-4">
            <a href="{{ route('web.sample-items.create') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus"></i>
                Create
            </a>
        </div>

        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#tab1" data-toggle="tab">Active</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#tab2" data-toggle="tab">Archived</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="tab1">
                            <sample-items-table
                            ref="table-1"
                            fetch-url="{{ route('web.sample-items.fetch') }}"
                            :filter-status="{{ $filterStatus }}"
                            ></sample-items-table>
                        </div>
                        <div class="tab-pane" id="tab2">
                            <sample-items-table
                            ref="table-2"
                            :auto-fetch="false"
                            fetch-url="{{ route('web.sample-items.fetch-archive') }}"
                            :filter-status="{{ $filterStatus }}"
                            ></sample-items-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection