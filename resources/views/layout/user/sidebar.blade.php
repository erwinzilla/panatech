<div class="bg-white">
    @include('sidebar.profile')
    <ul class="nav nav-pills flex-column" id="sidebar">
        @if($type != 'profile')
            <li class="nav-item mb-1">
                <a href="{{ $type == 'data' ? '#main' : url('user') }}" class="nav-link" aria-current="page">@svg('heroicon-o-users', 'icon me-2') User / Employee</a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ $type == 'create' ? '#main' : url('user/create') }}" class="nav-link">@svg('heroicon-o-plus', 'icon me-2') Create new</a>
            </li>
        @endif
        @if($type == 'create' || $type == 'edit' || $type == 'profile')
            <li class="nav-item mb-1">
                <a href="#main" class="nav-link text-secondary">@svg('heroicon-o-ellipsis-vertical', 'icon me-2') Account</a>
            </li>
            <li class="nav-item mb-1">
                <a href="#profile" class="nav-link text-secondary">@svg('heroicon-o-ellipsis-vertical', 'icon me-2') Personal Information</a>
            </li>
        @endif
        @if($type != 'profile')
            <li class="nav-item mb-1">
                <a href="{{ url('user/privilege') }}" class="nav-link">@svg('heroicon-o-credit-card', 'icon me-2') Privilege</a>
            </li>
            {{--            Jika all access maka munculkan element--}}
            @if(getUserLevel('users') >= ALL_ACCESS)
                <li class="nav-item">
                    <a href="{{ $type == 'trash' ? '#main' : url('user/trash') }}" class="nav-link text-danger">@svg('heroicon-o-trash', 'icon me-2') Trash</a>
                </li>
            @endif
        @endif
    </ul>
    <hr class="border-dashed">
    @include('sidebar.quick')
</div>