<div class="main-sidebar">
    @include('sidebar.profile')
    <ul class="nav nav-pills flex-column" id="sidebar">
        <li class="nav-item mb-1">
            <a href="{{ $type == 'data' ? '#main' : url($config['url']) }}" class="nav-link" aria-current="page">@svg('heroicon-o-tag', 'icon') Job Type</a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ $type == 'create' ? '#main' : url($config['url'].'/create') }}" class="nav-link">@svg('heroicon-o-plus', 'icon') Create New</a>
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