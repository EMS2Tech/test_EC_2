<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('frontend/assets/css/style.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        @include('layouts.navigation')
        <div class="container-fluid">
            @if (Auth::check())
                <div class="debug-info" style="display: none;">
                    User ID: {{ Auth::user()->id }}
                    User Type: {{ Auth::user()->type ?? 'null' }}
                    Session Status: {{ session('status') ?? 'none' }}
                </div>
            @endif
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('sweetalert')
    @yield('scripts')
</body>
</html>