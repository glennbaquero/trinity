@extends('web.auth-dashboard')

@section('pageTitle', 'Login')

@section('content')

<div class="login-box-msg">
    <p>Sign in to start your session</p>
</div>

<form action="{{ route('web.login') }}" method="POST">
    @csrf

    <div class="form-group has-feedback">
         <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>

        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group has-feedback">
        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Password" required>

        @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>

    <div class="mb-3">
        <a class="float-right" href="{{ route('web.password.request') }}">I forgot my password</a><br>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6 form-group">
            <a href="{{ route('web.register') }}" class="btn btn-default btn-block">Sign Up</a>
        </div>

        <div class="col-sm-12 col-md-6 form-group">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        </div>
    </div>
</form>

@endsection