<div class="main-sidebar">
    @include('sidebar.profile')
    <ul class="nav nav-pills flex-column">
        <li class="nav-item mb-1">
            <a class="nav-link active" aria-current="page" href="#">@svg('heroicon-o-home', 'icon') Home</a>
        </li>
        <li class="nav-item mb-1">
            <a class="nav-link" aria-current="page" href="#">@svg('heroicon-o-list-bullet', 'icon') My Task</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">@svg('heroicon-o-clock', 'icon') Recent</a>
        </li>
    </ul>
    <hr class="border-dashed">
    @include('sidebar.quick')
</div>