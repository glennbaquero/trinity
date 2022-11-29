<!DOCTYPE html>
<html class="no-js" lang="{{ app()->getLocale() }}">
<head>

    @include('partials.meta-tags')
    @include('admin.partials.styles')

</head>
<body class="hold-transition sidebar-mini">
	
    <div id="app" class="wrapper">

        @include('web.dashboard-partials.header')
        @include('web.dashboard-partials.sidebar')

        
        @yield('content')

        @include('web.dashboard-partials.footer')

    </div>

    @include('partials.script-tags')

</body>
</html>