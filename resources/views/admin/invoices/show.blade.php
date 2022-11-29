@extends('admin.master')

@section('pageTitle', 'Invoice # '. $invoice->invoice_number)

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $invoice->invoice_number }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.invoices.index') }}">Invoices</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ $invoice->invoice_number }}</a></li>
                </ol>
            </div>
        </div>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-md-3 form-group">
                <div class="row">
                    <invoice-status-update
                    :invoice="{{ $invoice }}"
                    :statuses="{{ $statuses }}"
                    submit-url="{{ route('admin.invoices.update') }}"                
                    ></invoice-status-update>
                </div>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-6 text-right form-group">
                <a href="{{ $invoice->renderPrintUrl() }}" target="_blank" class="btn btn-primary"><i class="fas fa-print"></i> Print</a>
            </div>
        </div>
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

                        <div class="form-group">
                            <h3>
                                <span class="badge badge-{{ $invoice->renderPaymentStatus()['status'] ? 'success' : 'danger' }}">
                                    {{ $invoice->renderPaymentStatus()['text'] }}
                                </span>
                            </h3>
                        </div>

                        <div class="form-group mt-5">
                            <h4>User Details</h4>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold text-secondary">Full Name:</label>
                                <h6 class="font-weight-bold">{{ $invoice->user->renderFullName() }}</h6>
                            </div>
                        

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold text-secondary">Email Address:</label>
                                <h6 class="font-weight-bold">{{ $invoice->user->email }}</h6>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold text-secondary">Contact Number:</label>
                                <h6 class="font-weight-bold">{{ $invoice->user->mobile_number ?? 'N/A' }}</h6>
                            </div>
                        </div>


                        @if($invoice->discount_card_path)
                        <hr />
                            <div class="form-group">
                                <h4>Uploaded Documents</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <a 
                                    target="_blank" 
                                    href="{{ $invoice->renderImagePath('discount_card_path') }}">
                                    <img class="img-thumbnail" 
                                    width="300px" height="300px" 
                                    src="{{ $invoice->renderImagePath('discount_card_path') }}">
                                    </a>
                                </div>

                            </div>
                        @endif

                        <hr />

                        <div class="form-group">
                            <h4>Order Details</h4>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold text-secondary">Checkout date:</label>
                                <h6 class="font-weight-bold">{{ $invoice->renderCreatedAt() }}</h6>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold text-secondary">Reference code:</label>
                                <h6 class="font-weight-bold">{{ $invoice->code }}</h6>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold text-secondary">Order Status:</label>
                                <h5>
                                    <span class="badge" style="background-color: {{ $invoice->status->bg_color }}; color: #fff;">
                                        {{ $invoice->status->name }}
                                    </span>
                                </h5>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold text-secondary">Payment status: </label>
                                <h5>
                                <label class="badge badge-{{ $invoice->renderPaymentStatus()['status'] ? 'success' : 'danger' }}">
                                    {{ $invoice->renderPaymentStatus()['text'] }}
                                </label>
                                </h5>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="font-weight-bold text-secondary">Item(s) bought:</label>
                                <h6 class="font-weight-bold">{{ $invoice->renderTotalItemsBought() }}</h6>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold text-secondary">Shipping fee:</label>
                                <h6 class="font-weight-bold">{{ $invoice->renderPrice('shipping_fee', 'Php') }}</h6>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold text-secondary">Discount:</label>
                                <h6 class="font-weight-bold">{{ $invoice->renderPrice('total_discount', 'Php') }}</h6>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold text-secondary">Grand total:</label>
                                <h6 class="font-weight-bold">{{ $invoice->renderPrice('grand_total', 'Php') }}</h6>
                            </div>

                            @if($invoice->userVoucher) 
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold text-secondary">Voucher code:</label>
                                    <h6 class="font-weight-bold">{{ $invoice->userVoucher->code }}</h6>
                                </div>
                            @endif

                        </div>

                        @if($invoice->failed_transaction)

                            <div class="form-group">
                                <h4>Failed Transaction Details</h4>
                            </div>                        

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>Updated at: </label> <label class="badge badge-danger">{{ $invoice->failed_transaction->renderDate() }}</label>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Reason: </label> <strong>{{ $invoice->failed_transaction->reason }}</strong>
                                </div>

                            </div>

                        @endif


                        <hr />

                        <div class="form-group">
                            <h4>Shipping Details</h4>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold text-secondary">Unit:</label>
                                <h6 class="font-weight-bold">{{ $invoice->shipping_unit }}</h6>
                            </div>
                        
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold text-secondary">Street:</label>
                                <h6 class="font-weight-bold">{{ $invoice->shipping_street }}</h6>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold text-secondary">Region:</label>
                                <h6 class="font-weight-bold">{{ $invoice->shipping_region }}</h6>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold text-secondary">Province:</label>
                                <h6 class="font-weight-bold">{{ $invoice->shipping_province }}</h6>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold text-secondary">City:</label>
                                <h6 class="font-weight-bold">{{ $invoice->shipping_city }}</h6>
                            </div>                            

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold text-secondary">Zip Code:</label>
                                <h6 class="font-weight-bold">{{ $invoice->shipping_zip }}</h6>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold text-secondary">Landmark:</label>
                                <h6 class="font-weight-bold">{{ $invoice->shipping_landmark ?? 'N/A' }}</h6>
                            </div>

                        </div>

                        <hr />

                        <invoice-items-table
                            fetch-url="{{ route('admin.invoice-items-fetch-items', $invoice->id) }}"
                        ></invoice-items-table>
                                
                    </div>
                    <div class="tab-pane" id="tab2">
                        <activity-logs-table 
                            ref="table-1"
                            :auto-fetch="false"
                            fetch-url="{{ route('admin.activity-logs.fetch.invoices', $invoice->id) }}"
                        ></activity-logs-table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection