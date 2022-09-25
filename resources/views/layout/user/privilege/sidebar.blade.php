<div class="main-sidebar">
    @include('sidebar.profile')
    <ul class="nav nav-pills flex-column" id="sidebar">
        <li class="nav-item mb-1">
            <a href="{{ $type == 'data' ? '#main' : url('user/privilege') }}" class="nav-link" aria-current="page">@svg('heroicon-o-credit-card', 'icon') Privilege</a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ $type == 'create' ? '#main' : url('user/privilege/create') }}" class="nav-link">@svg('heroicon-o-plus', 'icon') Create new</a>
        </li>
        {{--            Jika all access maka munculkan element--}}
        @if(getUserLevel('users') >= ALL_ACCESS)
            <li class="nav-item">
                <a href="{{ $type == 'trash' ? '#main' : url('user/privilege/trash') }}" class="nav-link text-danger">@svg('heroicon-o-trash', 'icon') Trash</a>
            </li>
        @endif
    </ul>
    <hr class="border-dashed">
    @include('sidebar.quick')
</div>