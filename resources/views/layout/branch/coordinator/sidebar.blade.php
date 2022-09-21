<div class="bg-white">
    @include('sidebar.profile')
    <ul class="nav nav-pills flex-column" id="sidebar">
        <li class="nav-item mb-1">
            <a href="{{ $type == 'data' ? '#main' : url('branch/coordinator') }}" class="nav-link" aria-current="page">@svg('heroicon-o-user-group', 'icon') Coordinator</a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ $type == 'create' ? '#main' : url('branch/coordinator/create') }}" class="nav-link">@svg('heroicon-o-plus', 'icon') Create new</a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ url('branch') }}" class="nav-link">@svg('heroicon-o-building-office-2', 'icon') Branch</a>
        </li>
        {{--            Jika all access maka munculkan element--}}
        @if(getUserLevel('branches') >= ALL_ACCESS)
            <li class="nav-item">
                <a href="{{ $type == 'trash' ? '#main' : url('branch/coordinator/trash') }}" class="nav-link text-danger">@svg('heroicon-o-trash', 'icon') Trash</a>
            </li>
        @endif
    </ul>
    <hr class="border-dashed">
    @include('sidebar.quick')
</div>