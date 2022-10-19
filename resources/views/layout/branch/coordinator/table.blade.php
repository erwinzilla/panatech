@include('component.table.header', ['perPage' => $table['perPage'], 'search' => $table['search']])
<div class="table-container">
    <table class="table table-striped mb-0 data-table align-middle">
        <thead>
        <tr>
            @include('component.table.title', ['title' => '#', 'column' => 'branch_coordinators.id', 'sortable' => true, 'class' => 'text-center'])
            @include('component.table.title', ['title' => 'Name', 'column' => 'branch_coordinators.name', 'sortable' => true])
            @include('component.table.title', ['title' => 'Coordinator', 'column' => 'users.name', 'sortable' => true])
            @include('component.table.title', ['title' => 'Privilege', 'column' => 'user_privileges.name', 'sortable' => false, 'class' => 'align-middle'])
            {{-- Jika can CRUD maka munculkan tombol--}}
            @if(getUserLevel($config['privilege']) >= CAN_CRUD)
                @include('component.table.title', ['title' => 'Action', 'column' => 'action', 'sortable'=> false, 'class' => 'text-center align-middle'])
            @endif
        </tr>
        </thead>
        <tbody>
        @if($data->total() == 0)
            <tr>
                @php
                    $colspan = 4;
                    if (getUserLevel($config['privilege']) >= CAN_CRUD) {
                        $colspan += 1; // ada baguian untuk action button
                    }
                @endphp
                <td class="text-muted text-center" colspan="{{ $colspan }}">No entries found</td>
            </tr>
        @else
            @foreach($data as $key => $row)
                <tr>
                    <td class="ps-3 text-muted w-1-slot">{{ $table['column'] == 'id' && $table['sort'] == 'desc' ? $data->total() - ($data->firstItem() + $key) + 1 : $data->firstItem() + $key }}</td>
                    <td>{{ $row->name }}</td>
                    <td>
                        @if($row->user)
                            <span>{{ $row->users->name }}</span>
                        @else
                            <span>-</span>
                        @endif
                    </td>
                    <td>
                        @if($row->user)
                            <span class="{{ getBadge($row->users->privileges->color) }}">{{ $row->users->privileges->name }}</span>
                        @else
                            <span>-</span>
                        @endif
                    </td>
                    {{--                                    Jika can CRUD maka munculkan tombol--}}
                    @if(getUserLevel($config['privilege']) >= CAN_CRUD)
                        <td class="pe-3 w-2-slot">
                            <div class="d-flex">
                                @include('form.button.crud', ['url' => 'branch/coordinator/', 'type' => $type, 'id' => $row->id])
                            </div>
                        </td>
                    @endif
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
@include('component.table.footer', ['total' => $data->total(), 'firstItem' => $data->firstItem(), 'lastItem' => $data->lastItem(), 'data' => $data])