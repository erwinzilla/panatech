<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    {{-- Bootstrap 5.1 custom css--}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Righteous|Poppins">
    <link href="{{ url('assets/css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
</head>
<body>
    <!-- Mini Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <div class="fs-7">
                <small>@svg('heroicon-o-speaker-wave', 'icon-sm') <b class="text-primary">Info:</b> Untuk update/info soal program ada disini tempatnya</small>
            </div>
            <div class="fs-6">
                <a href="#" target="_blank" class="text-muted me-2">@svg('heroicon-o-lifebuoy', 'icon-sm')</a>
                <a href="#" target="_blank" class="text-muted me-2">@svg('heroicon-s-language', 'icon-sm')</a>
                <a href="#" target="_blank" class="text-muted me-2">@svg('heroicon-s-sun', 'icon-sm')</a>
                <a href="{{ url('logout') }}" class="text-danger">@svg('heroicon-s-arrow-right-on-rectangle', 'icon-sm')</a>
            </div>
        </div>
    </nav>
    <!-- Main Navbar -->
    <nav class="navbar navbar-expand-lg border-0 py-0">
        <div class="container-fluid">
            <a class="navbar-brand fs-2" href="#"><span class="font-logo">PANA<span class="text-primary">TECH</span><span class="text-secondary">.</span></span></a>
            <form class="w-50" role="search">
                <div class="input-group">
                    <input class="form-control border-end-0" type="search" placeholder="Coba ketikan 'Daftar Produk' ..." aria-label="Search">
                    <span class="input-group-text border-start-0"><i class="bi-search"></i></span>
{{--                    <button class="btn btn-primary text-white" type="submit"><i class="bi-search"></i></button>--}}
                </div>
            </form>
        </div>
    </nav>
    <!-- Menu Navbar -->
    <nav class="navbar navbar-expand-lg shadow-sm pt-0">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbar-menu">
                <ul class="navbar-nav nav-pills">
                    <li class="nav-item me-2">
                        <a href="#" class="nav-link active" aria-expanded="false">Home</a>
                    </li>
                    <li class="nav-item me-2">
                        <a href="#" class="nav-link" aria-expanded="false">Ticket</a>
                    </li>
                    <li class="nav-item me-2">
                        <a href="#" class="nav-link" aria-expanded="false">Customer</a>
                    </li>
                    <li class="nav-item me-2">
                        <a href="#" class="nav-link" aria-expanded="false">Product</a>
                    </li>
                    <li class="nav-item me-2">
                        <a href="#" class="nav-link" aria-expanded="false">Sparepart</a>
                    </li>
                    <li class="nav-item dropdown me-2">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Account</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('user') }}" class="dropdown-item">@svg('heroicon-s-users', 'icon') User</a></li>
                            <li><a href="{{ url('user/privilege') }}" class="dropdown-item">@svg('heroicon-s-credit-card', 'icon') Privilege</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-content">
        {{--    Sidebar --}}
        <section class="sidebar bg-white">
            @yield('sidebar')
        </section>

        {{--    Content--}}
        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

{{--    Place for script--}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>

    @if(session('status'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                //show alert
                alert.fire({
                    title: '{{ ucwords(session('status')) }}',
                    text: '{{ session('message') }}',
                    icon: '{{ session('status') }}',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                })
            })
        </script>
    @endif
</body>
</html>
