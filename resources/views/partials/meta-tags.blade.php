<base href="/" />
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{{ config('app.name') }} | @yield('pageTitle', 'Login')</title>
<meta name="author" content="{{ config('app.name') }}">

<meta name="description" content="@yield('meta:description', 'Laravel - The PHP framework for web artisans.')">
<meta name="keywords" content="@yield('meta:keywords', 'laravel, php, framework, web, artisans, boilerplate')">

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link rel="apple-touch-icon" sizes="57x57" href="/favicons/favicon.png">
<link rel="apple-touch-icon" sizes="60x60" href="/favicons/favicon.png">
<link rel="apple-touch-icon" sizes="72x72" href="/favicons/favicon.png">
<link rel="apple-touch-icon" sizes="76x76" href="/favicons/favicon.png">
<link rel="apple-touch-icon" sizes="114x114" href="/favicons/favicon.png">
<link rel="apple-touch-icon" sizes="120x120" href="/favicons/favicon.png">
<link rel="apple-touch-icon" sizes="144x144" href="/favicons/favicon.png">
<link rel="apple-touch-icon" sizes="152x152" href="/favicons/favicon.png">
<link rel="apple-touch-icon" sizes="180x180" href="/favicons/favicon.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/favicons/favicon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon.png">
<link rel="icon" type="image/png" sizes="96x96" href="/favicons/favicon.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon.png">
<link rel="manifest" href="/favicons/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/favicons/favicon.png">
<meta name="theme-color" content="#ffffff">
<meta name="msapplication-navbutton-color" content="#1A1A18">
<meta name="apple-mobile-web-app-status-bar-style" content="#1A1A18">

<meta property="og:image" content="@yield('og:image', url('/') . '/images/logo.png')">
<meta property="og:title" content="@yield('og:title', config('app.name'))">
<meta property="og:description" content="@yield('og:description', 'In Information Technology, a boilerplate is a unit of writing that can be reused over and over without change. By extension, the idea is sometimes applied to reusable programming, as in boilerplate code.')">

<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:type" content="website">

<meta name="csrf-token" content="{{ csrf_token() }}">