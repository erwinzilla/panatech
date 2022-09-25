<!-- Mini Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <div class="fs-7">
            <small>@svg('heroicon-o-speaker-wave', 'icon-sm') <b class="text-primary">Info:</b> Untuk update/info soal program ada disini tempatnya</small>
        </div>
        <div class="fs-6">
            <a href="#" target="_blank" class="text-muted me-2">@svg('heroicon-o-lifebuoy', 'icon-sm')</a>
            <a href="#" target="_blank" class="text-muted me-2">@svg('heroicon-s-language', 'icon-sm')</a>
            <a id="btn-mode" href="#" class="text-muted me-2 text-decoration-none">
                @include('component.icon.theme', ['theme' => Auth::user()->theme])
            </a>
            <a href="{{ url('logout') }}" class="text-danger">@svg('heroicon-s-arrow-right-on-rectangle', 'icon-sm')</a>
        </div>
    </div>
</nav>