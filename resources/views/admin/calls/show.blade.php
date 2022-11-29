@extends('admin.master')

@section('pageTitle', 'Update Call')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $call->renderName() }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.calls.index') }}">Calls</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">#{{ $call->id }}</a></li>
                </ol>
            </div>
        </div>
    </section>
    
    <page-pagination fetch-url="{{ route('admin.calls.fetch-pagination', $call->id) }}"></page-pagination>

    <section class="content">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#tab1" data-toggle="tab">Information</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab2" data-toggle="tab">Documents</a></li>
                    <li class="nav-item"><a @click="initList('table-1')" class="nav-link" href="#tab3" data-toggle="tab">Activity Logs</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane show active" id="tab1">
                        <calls-view
                        fetch-url="{{ route('admin.calls.fetch-item', $call->id) }}"
                        submit-url="{{ route('admin.calls.update', $call->id) }}"
                        ></calls-view>
                    </div>

                    <div class="tab-pane show" id="tab2">
                        
                        <h4>Documents</h4>
                        <div class="row">
                            @foreach($call->callAttachments as $attachment)
                                @if($attachment->type != 2)
                                    <div class="form-group col-md-4">
                                        <a href="{{ $attachment->renderShowUrl() }}" target="_blank">
                                            {{ $attachment->name }}
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                            @if(!count($call->callAttachments))
                                <div class="col-md-12 form-group text-center">
                                    <label>No documents</label>
                                </div>
                            @endif
                        </div>
                        <hr />
                        <h4>Signature</h4>
                        <div class="row">
                            @foreach($call->callAttachments as $attachment)
                                @if($attachment->type === 2)
                                    <div class="form-group col-md-4">
                                        <img src="{{ $attachment->renderShowUrl() }}" width="200px" class="img-responsive">
                                    </div>
                                @endif
                            @endforeach

                            @if(!count($call->callAttachments))
                                <div class="col-md-12 form-group text-center">
                                    <label>No signature</label>
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="tab-pane" id="tab3">
                        <activity-logs-table 
                        ref="table-1"
                        :auto-fetch="false"
                        fetch-url="{{ route('admin.activity-logs.fetch.calls', $call->id) }}"
                        ></activity-logs-table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection