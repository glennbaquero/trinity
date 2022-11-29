<!DOCTYPE html>
<html class="no-js" lang="{{ app()->getLocale() }}">
<head>

    @include('partials.meta-tags')
    @include('web.partials.styles')

</head>
<body>

    <div id="app">

        @include('web.partials.header')
        
        @yield('content')

        @include('web.partials.footer')

    </div>

    @include('partials.script-tags')
	@yield('js')
</body>
</html>