@extends('admin.master')

@section('pageTitle', 'Create Sponsorship')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Sponsorship</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.sponsorships.index') }}">Sponsorship</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <sponsorships-view
            fetch-url="{{ route('admin.sponsorships.fetch-item') }}"
            submit-url="{{ route('admin.sponsorships.store') }}"
        ></sponsorships-view>
    </section>
</div>

@endsection