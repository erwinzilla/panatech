<div class="main-sidebar">
    @include('sidebar.profile')
    <ul class="nav nav-pills flex-column" id="sidebar">
        <li class="nav-item mb-1">
            <a href="{{ $type == 'data' ? '#main' : url($config['url']) }}" class="nav-link" aria-current="page">@svg('heroicon-o-building-storefront', 'icon') Service</a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ $type == 'create' ? '#main' : url($config['url'].'/create') }}" class="nav-link">@svg('heroicon-o-plus', 'icon') Create new</a>
        </li>
        @if($type == 'create' || $type == 'edit')
            <li class="nav-item mb-1">
                <a href="#main" class="nav-link text-secondary">@svg('heroicon-o-ellipsis-vertical', 'icon me-2') Information</a>
            </li>
            <li class="nav-item mb-1">
                <a href="#branch" class="nav-link text-secondary">@svg('heroicon-o-ellipsis-vertical', 'icon me-2') Branch</a>
            </li>
            <li class="nav-item mb-1">
                <a href="#submit" class="nav-link text-secondary">@svg('heroicon-o-ellipsis-vertical', 'icon me-2') Submit</a>
            </li>
        @endif
        <li class="nav-item mb-1">
            <a href="{{ url('branch') }}" class="nav-link">@svg('heroicon-o-building-office-2', 'icon') Branch</a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ url('branch/coordinator') }}" class="nav-link">@svg('heroicon-o-user-group', 'icon') Coordinator</a>
        </li>
        {{--            Jika all access maka munculkan element--}}
        @if(getUserLevel($config['privilege']) >= ALL_ACCESS)
            <li class="nav-item">
                <a href="{{ $type == 'trash' ? '#main' : url($config['url'].'/trash') }}" class="nav-link text-danger">@svg('heroicon-o-trash', 'icon') Trash</a>
            </li>
        @endif
    </ul>
    <hr class="border-dashed">
    @include('sidebar.quick')
</div>