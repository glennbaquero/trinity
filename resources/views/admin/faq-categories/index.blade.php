@extends('admin.master')

@section('pageTitle', 'FAQ Categories')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>FAQ Categories</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">FAQ Categories</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="mb-4">
            <a href="{{ route('admin.faq-categories.create') }}" class="btn btn-primary text-white">
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
                            <faq-categories-table 
                            ref="table-1"
                            fetch-url="{{ route('admin.faq-categories.fetch') }}"
                            ></faq-categories-table>
                        </div>
                        <div class="tab-pane" id="tab2">
                            <faq-categories-table
                            ref="table-2"
                            :auto-fetch="false"
                            fetch-url="{{ route('admin.faq-categories.fetch-archive') }}"
                            ></faq-categories-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection