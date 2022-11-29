@extends('admin.master')

@section('pageTitle', 'Update ' .$sponsorship->name)

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $sponsorship->name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.sponsorships.index') }}">Sponsorships</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ $sponsorship->name}}</a></li>
                </ol>
            </div>
        </div>
    </section>
    
    <section class="content">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#tab1" data-toggle="tab">Information</a></li>
                    <li class="nav-item"><a @click="initList('table-1')" class="nav-link" href="#tab2" data-toggle="tab">Activity Logs</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane show active" id="tab1">
                        <sponsorships-view
                            fetch-url="{{ route('admin.sponsorships.fetch-item', $sponsorship->id) }}"
                            submit-url="{{ route('admin.sponsorships.update', $sponsorship->id) }}"
                        ></sponsorships-view>
                    </div>
                    <div class="tab-pane" id="tab2">
                        <activity-logs-table 
                            ref="table-1"
                            :auto-fetch="false"
                            fetch-url="{{ route('admin.activity-logs.fetch.sponsorships', $sponsorship->id) }}"
                        ></activity-logs-table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection