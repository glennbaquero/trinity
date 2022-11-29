@extends('admin.master')

@section('pageTitle', 'Create '. $variant ? 'Product Variant' : 'Product')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create {{ $variant ? 'Product Variant' : 'Product' }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Create</a></li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <products-view
            fetch-url="{{ route('admin.products.fetch-item', $product ? $product->id : '') }}"
            submit-url="{{ route('admin.products.store', $product ? $product->id : '') }}"
            variant="{{ $variant }}"
        ></products-view>
    </section>
</div>

@endsection