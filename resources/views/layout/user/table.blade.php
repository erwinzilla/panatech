@include('component.table.header', ['perPage' => $table['perPage'], 'search' => $table['search']])
<div class="table-container">
    <table class="table table-striped mb-0 data-table align-middle">
        <thead>
        <tr>
            @include('component.table.title', ['title' => '#', 'column' => 'users.id', 'sortable' => true, 'class' => 'text-center'])
            @if($type == 'data' || $type == 'trash')
                @include('component.table.title', ['title' => 'Image', 'column' => 'users.image', 'sortable' => false, 'class' => 'align-middle'])
            @endif
            @include('component.table.title', ['title' => $type == 'data' || $type == 'trash' ? 'Name' : 'Name / Detail', 'column' => 'name', 'sortable' => true])
            @if($type == 'data' || $type == 'trash')
                @include('component.table.title', ['title' => 'Username', 'column' => 'users.username', 'sortable' => true])
                @include('component.table.title', ['title' => 'Address', 'column' => 'users.address', 'sortable' => true])
                @include('component.table.title', ['title' => 'Email', 'column' => 'users.email', 'sortable' => true])
            @endif
            @include('component.table.title', ['title' => 'Phone', 'column' => 'users.phone', 'sortable' => true])
            @include('component.table.title', ['title' => 'Privilege', 'column' => 'user_privileges.name', 'sortable' => true])
            {{-- Jika can CRUD maka munculkan tombol--}}
            @if(getUserLevel($config['privilege']) >= CAN_CRUD && ($type == 'data' || $type == 'trash'))
                @include('component.table.title', ['title' => 'Action', 'column' => 'action', 'sortable'=> false, 'class' => 'text-center align-middle'])
            @endif
            @if($type == 'choose')
                @include('component.table.title', ['title' => 'Action', 'column' => 'action', 'sortable'=> false, 'class' => 'text-center align-middle'])
            @endif
        </tr>
        </thead>
        <tbody>
        @if($data->total() == 0)
            <tr>
                @php
                    $colspan = 8;
                    if (getUserLevel($config['privilege']) >= CAN_CRUD) {
                        $colspan += 1; // ada baguian untuk action button
                    }
                    if ($type == 'choose') {
                        $colspan = 4;
                    }
                @endphp
                <td class="text-muted text-center" colspan="{{ $colspan }}">No entries found</td>
            </tr>
        @else
            @foreach($data as $key => $row)
                <tr>
                    <td class="ps-3 text-muted w-1-slot">{{ $table['column'] == 'id' && $table['sort'] == 'desc' ? $data->total() - ($data->firstItem() + $key) + 1 : $data->firstItem() + $key }}</td>
                    @if($type == 'data' || $type == 'trash')
                        <td>
                            @if($row->image)
                                <img src="{{ asset('uploads/images/users/'.$row->image) }}" class="avatar rounded-circle" alt="Avatar">
                            @else
                                <img src="{{ asset('uploads/images/users/default.jpg') }}" class="avatar rounded-circle" alt="Avatar">
                            @endif
                        </td>
                    @endif
                    <td>
                        @if($type == 'data' || $type == 'trash')
                            <span>{{ $row->name }}</span>
                            @if($row->branch_service)
                                <br><span class="{{ getBadge('primary') }}">{{ $row->branch_services->code }}</span>
                            @endif
                        @endif
                        @if($type == 'choose')
                            <b>{{ $row->name }}</b>
                            <br><small class="text-muted">{{ $row->address }}</small>
                        @endif
                    </td>
                    @if($type == 'data' || $type == 'trash')
                        <td>{{ $row->username }}</td>
                        <td>{{ $row->address }}</td>
                        <td><a href="mailto:{{ $row->email }}" target="_blank">{{ $row->email }}</a></td>
                    @endif
                    <td>{{ $row->phone }}</td>
                    <td>
                        @if($row->privilege)
                            <span class="{{ getBadge($row->privileges->color) }}">{{ $row->privileges->name }}</span>
                        @endif
                    </td>
                    {{--                                    Jika can CRUD maka munculkan tombol--}}
                    @if(getUserLevel($config['privilege']) >= CAN_CRUD && ($type == 'data' || $type == 'trash'))
                        <td class="pe-3 w-2-slot">
                            <div class="d-flex">
                                @include('form.button.crud', ['url' => 'user/', 'type' => $type, 'id' => $row->id])
                            </div>
                        </td>
                    @endif
                    @if($type == 'choose')
                        <td class="w-1-slot text-center">
                            <button type="button" class="btn btn-success btn-icon" data-bs-toggle="tooltip" data-bs-title="Choose" onclick="choose('user', {{ $row->id }});return false;" data-bs-dismiss="modal">
                                @svg('heroicon-s-check-circle', 'icon')
                            </button>
                        </td>
                    @endif
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
@include('component.table.footer', ['total' => $data->total(), 'firstItem' => $data->firstItem(), 'lastItem' => $data->lastItem(), 'data' => $data])