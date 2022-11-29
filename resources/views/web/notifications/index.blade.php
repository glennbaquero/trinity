@extends('web.master-dashboard')

@section('pageTitle', 'Notifications')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Notifications</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Notifications</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a @click="initList('notifications-unread')" class="nav-link active" href="#tab1" data-toggle="tab">Unread</a></li>
                    <li class="nav-item"><a @click="initList('notifications-read')" class="nav-link" href="#tab2" data-toggle="tab">Read</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane show active" id="tab1">
                        <notifications-table 
                        ref="notifications-unread"
                        read-all-url="{{ route('web.notifications.read-all') }}"
                        fetch-url="{{ route('web.notifications.fetch') }}"
                        ></notifications-table>
                    </div>
                    <div class="tab-pane" id="tab2">
                        <notifications-table 
                        ref="notifications-read"
                        unreadable
                        :auto-fetch="false"
                        fetch-url="{{ route('web.notifications.fetch-read') }}"
                        ></notifications-table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection