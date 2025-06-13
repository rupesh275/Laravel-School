<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <!-- Bootstrap 5 (as per user info) - will be properly set up with Vite/Mix later -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/admin-custom.css') }}"> --}}
    <style>
        body { font-family: 'Nunito', sans-serif; }
        .sidebar { min-height: 100vh; background-color: #f8f9fa; }
        .content-wrapper { padding-top: 20px; }
    </style>
    @stack('page_css')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-light">
        @include('admin.partials.header')

        <div class="container-fluid">
            <div class="row">
                @include('admin.partials.sidebar')

                <!-- Page Content -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content-wrapper">
                    @yield('content')
                </main>
            </div>
        </div>

        @include('admin.partials.footer')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script src="{{ mix('js/app.js') }}" defer></script> --}}
    <script>
        var base_url = "{{ url('/') }}";
        // Add other global JS variables if needed from CI header
    </script>
    @stack('page_scripts')
</body>
</html>
