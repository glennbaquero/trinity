@extends('admin.auth-master')

@section('pageTitle', 'Change Password')

@section('content')

<div class="login-box-msg">
    <small>Please enter your new password</small>
</div>

<form method="POST" action="{{ route('admin.password.change') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

    @if ($errors->has('email'))
        <p class="text-danger">{{ $errors->first('email') }}</p>
    @endif

    <div class="form-group has-feedback">
        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="New Password" required>

        @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group has-feedback">
        <input id="password" type="password" class="form-control" name="password_confirmation" placeholder="Confirm New Password" required>
    </div>
    
    <div class="row">
        <!-- /.col -->
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary btn-block">
                {{ __('Change Password') }}
            </button>
        </div>
        <!-- /.col -->
    </div>
</form>
@endsection
