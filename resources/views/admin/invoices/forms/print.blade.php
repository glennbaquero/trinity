<!DOCTYPE html>
<html class="no-js" lang="{{ app()->getLocale() }}">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>	
	<title>Order: {{ $invoice->invoice_number }}</title>
	<style>
		@import url(http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600);
        /* Reset it all! */
        html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6,
        p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del,
        dfn, em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup,
        tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form,
        label, legend, table, caption, tbody, tfoot, thead, tr, th, td,
        article, aside, canvas, details, figcaption, figure, footer,
        header, hgroup, menu, nav, section, summary, time, mark, audio, video {
            margin: 0;
            padding: 0;
            border: 0;
            outline: 0;
            vertical-align: baseline;
            font-family: 'Source Sans Pro', sans-serif;
            font-weight: normal;
        }

		* {
			margin: 0;
			font-family: Source Sans Pro;
		}

		label, p, th, small {
			font-size: 13px;
			line-height: .5em;
		}
		.bold {
			font-weight: bold;
		}
		.clearfix::after {
			content: "";
			clear: both;
			display: table;
		}

		.invoice__header-container {
			margin-bottom: 20px;
			margin-top: 30px;
		}
		.invoice__shipping-container {
			margin-top: 20px;	
			margin-bottom: 20px;
		}
		.invoice__header {
			font-size: 50px;
			font-weight: bold;
		}
		.invoice__trinity {
			font-size: 20px;
		}
		.invoice__body {
			padding: 10px;
		}
		
		table {
			width: 100%;
			margin-top: 10px;
			border-collapse: collapse;
		}
		thead {
			background: #e8e1e1;
		}
		th {
			padding: 10px;
			font-weight: bold;
			text-align: left;
		}
		td {
			padding: 10px;
		}
		.invoice__img {
			width: 70px;
		}
		.invoice__grand-total {
			font-size: 18px;
			color: #f5443b;
		}
		.form__row {
			width: 100%;
			position: relative;		
		}
		.width--50 {
			width: 50%;
			float: left;
			display: inline-block;
			vertical-align: baseline;			
			word-wrap: break-word;			
		}
	</style>
</head>
<body>
	<div class="invoice__body">

		<div class="invoice__header-container">
			<label class="invoice__header">INVOICE</label>
			<small class="bold invoice__trinity">Trinity Health Philippines</small>
		</div>
		<div class="form__row">
			<div class="width--50">
				<p class="bold">Invoice Number</p>
				<p>{{ $invoice->invoice_number }}</p>
				
				<div class="invoice__shipping-container">
					<p class="bold">Ship to</p>
					<p>{{ $invoice->user->renderFullName() }}</p>
					<p>{{ $invoice->user->email }}</p>
					<p>{{ $invoice->user->mobile_number }}</p>
					<p>{{ $invoice->renderFullAddress() }}</p>
				</div>

			</div>

			<div class="width--50">
				<p class="bold">Date Issued</p>
				<p>{{ $invoice->created_at }}</p>
				<div class="invoice__shipping-container">
					<p class="bold">Order details</p>
					<p>Code: {{ $invoice->code }}</p>
					<p>Grand Total: {{ $invoice->renderGrandTotal(true) }}</p>
					<p>Order Status: {{ $invoice->renderOrderStatus()->name }}</p>
					<p>Payment Status: {{ $invoice->renderPaymentStatus()['text'] }}</p>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		
		<div style=": center;">	
			<table>
				<thead>
					<tr>
						<th></th>
						<th>Product Name</th>
						<th>Quantity</th>
						<th>Price Per Unit</th>
						<th>Line total</th>
					</tr>
				</thead>
				<tbody>
					@foreach($invoice->invoiceItems as $item)
						<tr>
							<td><img class="invoice__img" src="{{ public_path($item->product->renderImagePath()) }}"></td>
							<td><label>{{ $item->renderEncodedItem()->name }}</label></td>
							<td><label>{{ $item->quantity }}</label></td>
							<td><label>{{ $item->renderEncodedItem()->price }}</label></td>
							<td><label>{{ $item->computeLineTotal(true) }}</label></td>
						</tr>
					@endforeach
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td align="left"><label class="bold">Sub total:</label></td>
						<td><label class="bold">{{ $invoice->renderSubTotal(true) }}</label></td>
					</tr>

					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td align="left"><label class="bold">Shipping fee:</label></td>
						<td><label class="bold">{{ $invoice->renderShippingFee(true) }}</label></td>
					</tr>

					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td align="left"><label class="bold">Grand Total:</label></td>
						<td><label class="invoice__grand-total bold">{{ $invoice->renderGrandTotal(true) }}</label></td>
					</tr>

				</tbody>
			</table>
		</div>

	</div>
</body>
</html>