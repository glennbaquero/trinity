@component('mail::message')
# Hello {{ $invoice->user->renderFullName() }}!

@component('mail::panel')

You have successfully placed your order. Please check the order details below thank you.

@endcomponent


<p><b>Order Number: {{ $invoice->invoice_number }}</b></p>

<p><b>Date of Purchase: {{ $invoice->created_at->toDayDateTimeString() }}</b></p>

<p><b>Sub Total: {{ $invoice->renderInvoiceSubtotal() }}</b></p>

<p><b>Shipping Fee: {{ $invoice->renderPrice('shipping_fee', 'Php') }}</b></p>

<p><b>Discount: {{ $invoice->renderPrice('total_discount', 'Php') }}</b></p>

<p><b>Total Payment: {{ $invoice->renderPrice('grand_total', 'Php') }}</b></p>


@component('mail::table')
|                         | Name                         | Quantity                      | Line Total                          |
|:-------------------------------------|:-------------------------------------|:------------------------------|:------------------------------------|
@foreach($invoice->invoiceItems as $item)
| <img class="prd__img" src="{{ $item->product->renderImagePath() }}"> | {{ $item->product->name }}           | {{ $item->quantity }}         | {{ $item->computeLineTotal(true) }}   |
@endforeach
@endcomponent

@if(in_array($invoice->payment_method, [4, 6]))

@component('mail::panel')

To upload your proof of payment, follow the steps below:

<p><b>Step 1:</b> Go to Profile and navigate to the Orders tab.</p>

<p><b>Step 2:</b> Navigate to your order.</p>

<p><b>Step 3:</b> Upload your bank deposit or proof of transaction under the Proof of Payment section.</p>

@endcomponent

@endif


Thank you for using {{ config('app.name') }}

	
Regards,<br>
{{ config('app.name') }}
@endcomponent

<style>
	.prd__img {
		width: 70px;
	}
</style>