@extends('web.master')

@section('pageTitle', 'Password Reset')

@section('content')

<div class="container mt-5">
	<div class="col-sm-12 text-sm-center mb-5">
		<h3>{{ config('app.name') }} | Password Reset</h3>
	</div>
	<div>
        <div class="col-md-6 offset-3" style="text-align: center;">
			<div class="alert alert-success" role="alert">
				<p>You have successfully update your password</p>
				You may now login with your new password
			</div>
		</div>
    </div>

</div>

@endsection