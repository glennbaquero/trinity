@extends('admin.master')

@section('pageTitle', 'Patients')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Patients</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Patients</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="mb-4">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary text-white">
                <i class="fa fa-plus"></i>
                Add a patient / secretary
            </a>
        </div>

        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#activity" data-toggle="tab">Active</a></li>
                    <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#timeline" data-toggle="tab">Archive</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane show active" id="activity">
                        <users-table 
                        ref="table-1"
                        fetch-url="{{ route('admin.users.fetch') }}"
                        ></users-table>
                    </div>
                    <div class="tab-pane" id="timeline">
                        <users-table
                        ref="table-2" 
                        :auto-fetch="false"
                        fetch-url="{{ route('admin.users.fetch-archive') }}"
                        ></users-table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection