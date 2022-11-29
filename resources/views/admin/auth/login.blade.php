@extends('admin.auth-master')

@section('pageTitle', 'Login')

@section('content')

<p class="login-box-msg">Sign in to start your session</p>

<form action="{{route('admin.login')}}" method="POST">
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
        <a class="float-right" href="{{ route('admin.password.request') }}">I forgot my password</a><br>
    </div>
    <div class="row">
        <!-- /.col -->
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        </div>
        <!-- /.col -->
    </div>
</form>

@endsection