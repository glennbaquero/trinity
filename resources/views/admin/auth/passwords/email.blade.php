@extends('admin.auth-master')

@section('pageTitle', 'Forgot Password')

@section('content')

<div class="login-box-msg">
    <h5>{{ __('Reset Password') }}</h5>
</div>

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('admin.password.email') }}">
    @csrf

   <div class="form-group has-feedback">
         <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Enter your Email Address" required autofocus>

        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>

    <div class="row">
        <!-- /.col -->
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary btn-block">
                {{ __('Send Password Reset Link') }}
            </button>
        </div>
        <!-- /.col -->
    </div>
</form>

@endsection
