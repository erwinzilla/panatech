<!-- Menu Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm pt-0">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbar-menu">
            <ul class="navbar-nav nav-pills">
                <li class="nav-item me-2">
                    <a href="{{ url('home') }}" class="nav-link {{ Request::segment(1) == 'home' ? 'active' : '' }}" aria-expanded="false">Home</a>
                </li>
                @if(getUserLevel('branches') > 0)
                    <li class="nav-item dropdown me-2">
                        <a href="#" class="nav-link dropdown-toggle {{ Request::segment(1) == 'branch' ? 'active' : '' }}" data-bs-toggle="dropdown" aria-expanded="false">Branches</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('branch') }}" class="dropdown-item">@svg('heroicon-s-building-office-2', 'icon-sm me-1') Branch</a></li>
                            <li><a href="{{ url('') }}" class="dropdown-item">@svg('heroicon-s-credit-card', 'icon-sm me-1') Coming Soon</a></li>
                        </ul>
                    </li>
                @endif
                {{--                    Jika only see maka munculkan tombol--}}
                @if(getUserLevel('users') > 0)
                    <li class="nav-item dropdown me-2">
                        <a href="#" class="nav-link dropdown-toggle {{ Request::segment(1) == 'user' ? 'active' : '' }}" data-bs-toggle="dropdown" aria-expanded="false">User / Employee</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('user') }}" class="dropdown-item">@svg('heroicon-s-users', 'icon-sm me-1') User</a></li>
                            <li><a href="{{ url('user/privilege') }}" class="dropdown-item">@svg('heroicon-s-credit-card', 'icon-sm me-1') Privilege</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>