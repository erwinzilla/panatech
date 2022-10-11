<!-- Main Navbar -->
<nav class="navbar navbar-expand-lg border-0 py-0">
    <div class="container-fluid">
        <a class="navbar-brand fs-2" href="{{ url('home') }}"><span class="font-logo">PANA<span class="text-primary">TECH</span><span class="text-secondary">.</span></span></a>
        <form class="w-50" role="search">
            <div class="input-group">
                <input class="form-control border-end-0" type="search" placeholder="Coba ketikan 'Daftar Produk' ..." aria-label="Search">
                <span class="input-group-text border-start-0 text-muted">
                    @svg('heroicon-s-magnifying-glass', 'icon')
                </span>
            </div>
        </form>
        <div class="d-flex">
            <a href="#" target="_blank" class="text-muted me-3">@svg('heroicon-s-lifebuoy', 'icon')</a>
            <a href="#" target="_blank" class="text-muted me-3">@svg('heroicon-s-language', 'icon')</a>
            <a id="btn-mode" href="#" class="text-muted me-3 text-decoration-none">
                @include('component.icon.theme', ['theme' => Auth::user()->theme])
            </a>
            <a href="{{ url('logout') }}" class="text-danger">@svg('heroicon-s-arrow-right-on-rectangle', 'icon')</a>
        </div>
    </div>
</nav>