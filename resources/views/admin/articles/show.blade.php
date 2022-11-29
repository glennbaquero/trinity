@extends('admin.master')

@section('pageTitle', $item->renderShortTitle())

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $item->renderShortTitle() }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.articles.index') }}">Articles</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ $item->renderShortTitle() }}</a></li>
                </ol>
            </div>
        </div>
    </section>
    
    <page-pagination fetch-url="{{ route('admin.articles.fetch-pagination', $item->id) }}"></page-pagination>

    <section class="content">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#tab1" data-toggle="tab">Information</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab2" data-toggle="tab">Comments and Replies</a></li>
                    <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#tab3" data-toggle="tab">Activity Logs</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane show active" id="tab1">
                        <articles-view
                        fetch-url="{{ route('admin.articles.fetch-item', $item->id) }}"
                        submit-url="{{ route('admin.articles.update', $item->id) }}"
                        :show="true"
                        ></articles-view>
                    </div>
                    <div class="tab-pane" id="tab2">
                        <comments-view
                        ref="table-1"
                        fetch-url="{{ route('admin.articles.comments', $item->id) }}"
                        ></comments-view>
                    </div>
                    <div class="tab-pane" id="tab3">
                        <activity-logs-table 
                        ref="table-2"
                        :auto-fetch="false"
                        fetch-url="{{ route('admin.activity-logs.fetch.articles', $item->id) }}"
                        ></activity-logs-table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection