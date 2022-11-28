<!-- Menu Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm pt-0 pb-2">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbar-menu">
            <ul class="navbar-nav nav-pills">
                <li class="nav-item me-2">
                    <a href="{{ url('home') }}" class="nav-link {{ Request::segment(1) == 'home' ? 'active' : '' }}" aria-expanded="false">Home</a>
                </li>
                @if(getUserLevel('customers') >= ONLY_SEE)
                    <li class="nav-item dropdown dropdown-hover me-2">
                        <a href="#" class="nav-link dropdown-toggle {{ Request::segment(1) == 'customer' ? 'active' : '' }}" data-bs-toggle="dropdown" aria-expanded="false">Customers</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('customer') }}" class="dropdown-item">@svg('heroicon-s-user', 'icon-sm me-1') Customer</a></li>
                            <li><a href="{{ url('customer/type') }}" class="dropdown-item">@svg('heroicon-s-user-group', 'icon-sm me-1') Type</a></li>
                        </ul>
                    </li>
                @endif
                @if(getUserLevel('warranties') >= ONLY_SEE)
                    <li class="nav-item dropdown dropdown-hover me-2">
                        <a href="{{ url('warranty') }}" class="nav-link {{ Request::segment(1) == 'warranty' ? 'active' : '' }}">Warranty</a>
                    </li>
                @endif
                @if(getUserLevel('tickets') >= ONLY_SEE)
                    <li class="nav-item dropdown dropdown-hover me-2">
                        <a href="{{ url('ticket') }}" class="nav-link {{ Request::segment(1) == 'ticket' ? 'active' : '' }}">Ticket</a>
                    </li>
                @endif
                {{-- Jika only see maka munculkan tombol--}}
                @if(getUserLevel('jobs') >= ONLY_SEE)
                    <li class="nav-item dropdown dropdown-hover me-2">
                        <a href="#" class="nav-link dropdown-toggle {{ Request::segment(1) == 'job' ? 'active' : '' }}" data-bs-toggle="dropdown" aria-expanded="false">Job</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('job') }}" class="dropdown-item">@svg('heroicon-s-briefcase', 'icon-sm me-1') Job</a></li>
                            <li><a href="{{ url('job/type') }}" class="dropdown-item">@svg('heroicon-s-tag', 'icon-sm me-1') Job Type</a></li>
                        </ul>
                    </li>
                @endif
                @if(getUserLevel('parts') >= ONLY_SEE)
                    <li class="nav-item dropdown dropdown-hover me-2">
                        <a href="{{ url('part') }}" class="nav-link {{ Request::segment(1) == 'part' ? 'active' : '' }}">Spare Part</a>
                    </li>
                @endif
                @if(getUserLevel('jobs') >= ONLY_SEE)
                    <li class="nav-item dropdown dropdown-hover me-2">
                        <a href="{{ url('invoice') }}" class="nav-link {{ Request::segment(1) == 'invoice' ? 'active' : '' }}">Invoice</a>
                    </li>
                @endif
                @if(getUserLevel('branches') >= ONLY_SEE)
                    <li class="nav-item dropdown dropdown-hover me-2">
                        <a href="#" class="nav-link dropdown-toggle {{ Request::segment(1) == 'branch' ? 'active' : '' }}" data-bs-toggle="dropdown" aria-expanded="false">Branches</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('branch') }}" class="dropdown-item">@svg('heroicon-s-building-office-2', 'icon-sm me-1') Branch</a></li>
                            <li><a href="{{ url('branch/coordinator') }}" class="dropdown-item">@svg('heroicon-s-user-group', 'icon-sm me-1') Coordinator</a></li>
                            <li><a href="{{ url('branch/service') }}" class="dropdown-item">@svg('heroicon-s-building-storefront', 'icon-sm me-1') Service</a></li>
                            <li><a href="{{ url('branch/service/target') }}" class="dropdown-item">@svg('heroicon-s-cursor-arrow-ripple', 'icon-sm me-1') Target</a></li>
                            <li><a href="{{ url('branch/service/sabbr') }}" class="dropdown-item">@svg('heroicon-s-chart-bar', 'icon-sm me-1') SABBR</a></li>
                        </ul>
                    </li>
                @endif
                {{--                    Jika only see maka munculkan tombol--}}
                @if(getUserLevel('users') >= ONLY_SEE)
                    <li class="nav-item dropdown dropdown-hover me-2">
                        <a href="#" class="nav-link dropdown-toggle {{ Request::segment(1) == 'user' ? 'active' : '' }}" data-bs-toggle="dropdown" aria-expanded="false">User / Employee</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('user') }}" class="dropdown-item">@svg('heroicon-s-users', 'icon-sm me-1') User</a></li>
                            <li><a href="{{ url('user/privilege') }}" class="dropdown-item">@svg('heroicon-s-credit-card', 'icon-sm me-1') Privilege</a></li>
                        </ul>
                    </li>
                @endif
                {{-- Jika only see maka munculkan tombol--}}
                @if(getUserLevel('misc') >= ONLY_SEE)
                    <li class="nav-item dropdown dropdown-hover me-2">
                        <a href="#" class="nav-link dropdown-toggle {{ Request::segment(1) == 'status' ? 'active' : '' }}" data-bs-toggle="dropdown" aria-expanded="false">Misc</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('status') }}" class="dropdown-item">@svg('heroicon-s-tag', 'icon-sm me-1') Job Status</a></li>
                            <li><a href="{{ url('config') }}" class="dropdown-item">@svg('heroicon-s-cog-8-tooth', 'icon-sm me-1') Config</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>