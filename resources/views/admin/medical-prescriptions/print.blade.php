<!DOCTYPE html>
<html class="no-js" lang="{{ app()->getLocale() }}">
<head>
	<title>{{ $consultation->consultation_number }}</title>
<style>
	@import url(http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600);

	* {
		margin: 0
	}

	body {
		font-family: 'Open Sans', sans-serif;
	}

	p {
		line-height: 1.5em;
	}
	.header {
		margin: 10px 10px;		
		padding: 20px;
		text-align: center;
	}

	.hr-line-solid {
		margin-left: 10%;
		margin-right: 10%;
		height: 3px;
		background: #000;
	}
	.hr-line {
		margin-left: 10%;
		margin-right: 10%;
		height: 1px;
		background: #000;
	}	

	.margin-1 {
		margin-top: 8px;
	}
	.margin-2 {
		margin-top: 10px;
	}

	.rx {
		margin-top: 10px;
		margin-left: 10%;
	}

	.col-50 {
		display: inline-block;
		width: 49%;
	}
	.footer {
		margin-right: 10%;		
		color: #000;
		text-align: right;
	}
	.text-right {
		text-align: right;
		margin-right: 8%;
	}

</style>
</head>
<body>
	<div>
		
		<div class="header">
			<h2>{{ $consultation->doctor->fullname }}</h2>
			<h3>{{ $consultation->doctor->specialization_name }}</h3>
			<h5>LIC No. {{ $consultation->doctor->license_number }}</h3>
		</div>

		<div class="hr-line-solid"></div>
		<div class="hr-line margin-1"></div>
		
		<div class="col-50">
			<div class="rx" style="margin-left: 20%">
				<label><b>Patient:</b> {{ $consultation->user->fullname }}</label>
			</div>
		</div>

		<div class="col-50">
			<div class="rx">
				<label><b>Date:</b> {{ $consultation->prescription->created_at }}</label>
			</div>
		</div>
		
		<div class="rx">
			<img src="{{ public_path('images/rx.png') }}" width="50px" height="50px">			
		</div>

		<div class="margin-2">
			@foreach($prescription->meds as $med)
				<div class="rx">
					<p><b>Name:</b> {{ $med->name }}</p>
					<p><b>Dosage:</b> {{ $med->dosage }}</p>
					<p><b>Duration:</b> {{ $med->duration }}</p>
					<p><b>Notes:</b> {{ $med->notes }}</p>				
				</div>

				<div class="hr-line margin-1"></div>
			@endforeach
		</div>		

	</div>

	<div class="footer">
		@if($consultation->doctor->signature)
			<div style="margin-top: 10px">
				<img 
				width="150px"
				height="120px" 
				src="{{ base_path() .'/storage/app/public/'. $consultation->doctor->signature }}"></div>
			</div>
		@endif
		<div class="text-right">
			<p>Dr. {{ $consultation->doctor->fullname }}</p>		
		</div>
	</div>

</body>
</html>