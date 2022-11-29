@extends('web.master-dashboard')

@section('pageTitle', 'My Account')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>My Account</h1>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#tab1" data-toggle="tab">Information</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab2" data-toggle="tab">Change Password</a></li>
                     <li class="nav-item"><a @click="initList('table-1')" class="nav-link" href="#tab3" data-toggle="tab">Activity Logs</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane show active" id="tab1">
                       <users-view
                       :editable="false"
                       fetch-url="{{ route('web.profiles.fetch') }}"
                       submit-url="{{ route('web.profiles.update') }}"
                       ></users-view>
                    </div>
                    <div class="tab-pane" id="tab2">
                        <change-password-form submit-url="{{ route('web.profiles.change-password') }}"></change-password-form>
                    </div>
                    <div class="tab-pane" id="tab3">
                        <activity-logs-table 
                        ref="table-1"
                        auto-fetch="false"
                        fetch-url="{{ route('web.activity-logs.fetch.profiles') }}" 
                        hide-causer
                        ></activity-logs-table>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection