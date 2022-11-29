@extends('admin.master')

@section('pageTitle', 'Request Claim Referrals')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Request Claim Referrals</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Request Claim Referrals</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a @click="initList('table-1')" class="nav-link active" href="#tab1" data-toggle="tab">Pending</a></li>
                        <li class="nav-item"><a @click="initList('table-2')" class="nav-link" href="#tab2" data-toggle="tab">Approved</a></li>
                        <li class="nav-item"><a @click="initList('table-3')" class="nav-link" href="#tab3" data-toggle="tab">Rejected</a></li>
                        <li class="nav-item"><a @click="initList('table-4')" class="nav-link" href="#tab4" data-toggle="tab">Archived</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="tab1">
                            <request-claim-referrals-table
                            ref="table-1"
                            fetch-url="{{ route('admin.request-claim-referrals.fetch-pending') }}"
                            :has-action="true"
                            ></request-claim-referrals-table>
                        </div>
                        <div class="tab-pane" id="tab2">
                            <request-claim-referrals-table
                            ref="table-2"
                            :auto-fetch="false"
                            fetch-url="{{ route('admin.request-claim-referrals.fetch-approved') }}"
                            ></request-claim-referrals-table>
                        </div>
                        <div class="tab-pane" id="tab3">
                            <request-claim-referrals-table
                            ref="table-3"
                            :auto-fetch="false"
                            fetch-url="{{ route('admin.request-claim-referrals.fetch-rejected') }}"
                            ></request-claim-referrals-table>
                        </div>                 
                        <div class="tab-pane" id="tab4">
                            <request-claim-referrals-table
                            ref="table-4"
                            :auto-fetch="false"
                            fetch-url="{{ route('admin.request-claim-referrals.fetch-archive') }}"
                            ></request-claim-referrals-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection