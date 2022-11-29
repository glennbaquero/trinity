@extends('admin.master')

@section('pageTitle', 'Create FAQ Category')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create FAQ Category</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.faq-categories.index') }}">FAQ Category</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <faq-categories-view
        fetch-url="{{ route('admin.faq-categories.fetch-item') }}"
        submit-url="{{ route('admin.faq-categories.store') }}"
        ></faq-categories-view>
    </section>
</div>

@endsection