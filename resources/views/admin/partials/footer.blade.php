<footer class="main-footer trinity__primary-color overflow-hidden text-light">
	@if (\App\Helpers\EnvHelpers::isDev())
    	<developer-mode label="{{ config('web.version') }}" fetch-url="{{ route('developer.users.fetch') }}" submit-url="{{ route('developer.users.change-account') }}"></developer-mode>
	@else
	    <div class="float-right d-none d-sm-block">
			<b>Version</b> {{ config('web.version') }}
	    </div>
	@endif
	<strong>&copy; Copyright {{ $now->year }} <a href="javascript:void(0)" class="text-info">{{ config('app.name') }}</a>.</strong> All rights reserved.
</footer>