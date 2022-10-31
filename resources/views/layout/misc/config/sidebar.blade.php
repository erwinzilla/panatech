<div class="main-sidebar">
    @include('sidebar.profile')
    <ul class="nav nav-pills flex-column" id="sidebar">
        <li class="nav-item mb-1">
            <a href="{{ $type == 'data' ? '#main' : url($config['url']) }}" class="nav-link" aria-current="page">@svg('heroicon-o-cog-8-tooth', 'icon') Configuration</a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ url('status') }}" class="nav-link">@svg('heroicon-o-tag', 'icon') Job Status</a>
        </li>
    </ul>
    <hr class="border-dashed">
    @include('sidebar.quick')
</div>