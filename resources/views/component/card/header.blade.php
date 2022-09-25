<div class="card-header {{ isset($class) ? $class : '' }}">
    @if($type == 'trash')
        <div class="align-self-center me-3 text-danger">
            @svg('heroicon-s-trash', 'icon', ['style' => 'width:40px;height:40px'])
        </div>
    @endif
    <div>
        <h4 class="text-default fw-bold mb-0">{{ ucwords($title) }}</h4>
        <small class="text-muted">{{ ucfirst($desc) }}</small>
    </div>
    {{--                Jika can CRUD maka munculkan tombol--}}
    @if(getUserLevel($config['privilege']) >= CAN_CRUD)
        <div class="align-self-center ms-auto">
            @if($type == 'data')
                <a href="{{ url($config['url'].'/create') }}" class="btn btn-primary">
                    @svg('heroicon-s-plus', 'icon') Create new
                </a>
            @endif
            @if($type == 'trash')
                <a href="{{ url($config['url'].'/restore') }}" class="btn btn-info me-2 restore-action-link">
                    @svg('heroicon-s-arrow-up-on-square-stack', 'icon') Restore All
                </a>
                <a href="{{ url($config['url'].'/delete') }}" class="btn btn-danger delete-action-link">
                    @svg('heroicon-s-trash', 'icon') Destroy All
                </a>
            @endif
        </div>
    @endif
</div>