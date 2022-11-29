@extends('web.master')

@section('content')

<div class="container mt-5">
	<div class="col-sm-12 text-sm-center mb-5">
		<h1>{{ config('app.name') }}</h1>
	</div>
	<div>
        <div style="text-align: center;">
		    <p>You have successfully update your password</p>
			@if(Agent::isDesktop())
				<div class="alert alert-success" role="alert")><center>You may now login with your new password</center>
				</div>
			@else
				<div class="alert alert-success" role="alert" onclick="window.open('trinitycare.app.scheme://','_system')">		<center>Click here to open the app</center>
				</div>
			@endif
		</div>
    </div>

</div>

@endsection

@section('js')
	@if(!Agent::isDesktop())
		<script>
			window.onload = function () { window.open('trinitycare.app.scheme://','_system') }
		</script>
	@endif
@endsection