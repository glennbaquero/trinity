@extends('admin.master')

@section('pageTitle', 'Update the request of '. $item->requester->renderFullName())

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Update the request of {{ $item->requester->renderFullName() }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.request-claim-referrals.index') }}">Request Claim Referrals</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ $item->requester->renderFullName() }}</a></li>
                </ol>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#tab1" data-toggle="tab">Information</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane show active" id="tab1">
                        <update-status
                        fetch-url="{{ route('admin.request-claim-referrals.fetch-item', $item->id) }}"
                        submit-url="{{ $submitUrl }}"
                        action="{{ $action }}"
                        ></update-status>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection