@extends('web.auth-dashboard')

@section('pageTitle', 'Register')

@section('content')

<div class="login-box-msg">
    <p>Sign up to start your session</p>
</div>

<form method="POST" action="{{ route('web.register') }}">
    @csrf

    <div class="form-group has-feedback">
         <input type="first_name" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" required autofocus>

        @if ($errors->has('first_name'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('first_name') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group has-feedback">
         <input type="last_name" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" required>

        @if ($errors->has('last_name'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('last_name') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group has-feedback">
         <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Email" required>

        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group has-feedback">
        <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Password" required>

        @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group has-feedback">
        <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
    </div>

    <div class="row">
        <!-- /.col -->
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </div>
    </div>
</form>
@endsection
