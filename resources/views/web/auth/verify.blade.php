@extends('web.auth-dashboard')

@section('pageTitle', 'Verify Account')

@section('content')
<div class="card">
    <div class="card-header">{{ __('Verify Your Email Address') }}</div>

    <div class="card-body">
        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
        @endif

        {{ __('Before proceeding, please check your email for a verification link.') }}
        {{ __('If you did not receive the email') }}, <a href="{{ route('web.verification.resend') }}">{{ __('click here to request another') }}</a>.
    </div>
</div>

<div class="row">
    <div class="col-sm-12 form-group">
        <a href="{{ route('web.logout') }}" class="btn btn-default btn-block">Logout</a>
    </div>
</div>
@endsection
