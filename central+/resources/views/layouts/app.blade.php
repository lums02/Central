<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    {{-- CSS Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS personnalisé --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

    {{-- Navbar --}}
    @include('layouts.partials.navbar')

    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar --}}
            @include('layouts.partials.leftsidebar')

            {{-- Contenu principal --}}
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Footer --}}
    @include('layouts.partials.footer')

    {{-- JS Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- JS personnalisé --}}
    <script src="{{ asset('js/dashboard.js') }}"></script>

</body>
</html>
