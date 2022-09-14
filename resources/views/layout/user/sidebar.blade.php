<div class="bg-white">
    @include('sidebar.profile')
    <ul class="nav nav-pills flex-column" id="sidebar">
        <li class="nav-item mb-1">
            <a href="{{ $type == 'data' ? '#main' : url('user') }}" class="nav-link" aria-current="page">@svg('heroicon-o-users', 'icon') User / Employee</a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ $type == 'create' ? '#main' : url('user/create') }}" class="nav-link">@svg('heroicon-o-plus', 'icon') Create new</a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ url('user/privilege') }}" class="nav-link">@svg('heroicon-o-credit-card', 'icon') Privilege</a>
        </li>
        {{--            Jika all access maka munculkan element--}}
        @if(Auth::user()->privileges->users > 2)
            <li class="nav-item">
                <a href="{{ $type == 'trash' ? '#main' : url('user/trash') }}" class="nav-link text-danger">@svg('heroicon-o-trash', 'icon') Trash</a>
            </li>
        @endif
    </ul>
    <hr class="border-dashed">
    @include('sidebar.quick')
</div>