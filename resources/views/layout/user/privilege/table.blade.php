@include('component.table.header', ['perPage' => $table['perPage'], 'search' => $table['search']])
<div class="table-container">
    <table class="table table-striped mb-0 data-table align-middle">
        <thead>
        <tr>
            @include('component.table.title', ['title' => 'No.', 'column' => 'id', 'sortable' => true, 'class' => 'text-center'])
            @include('component.table.title', ['title' => 'Name', 'column' => 'name', 'sortable' => true])
            @include('component.table.title', ['title' => 'Color', 'column' => 'color', 'sortable' => true])
            {{-- Jika can CRUD maka munculkan tombol--}}
            @if(getUserLevel('users') >= CAN_CRUD)
                @include('component.table.title', ['title' => 'Action', 'column' => 'action', 'sortable'=> false, 'class' => 'text-center'])
            @endif
        </tr>
        </thead>
        <tbody>
        @if($data->total() == 0)
            <tr>
                @php
                    $colspan = 3;
                    if (getUserLevel('users') >= CAN_CRUD) {
                        $colspan += 1; // ada baguian untuk action button
                    }
                @endphp
                <td class="text-muted text-center" colspan="{{ $colspan }}">No entries found</td>
            </tr>
        @else
            @foreach($data as $key => $row)
                <tr>
                    <td class="text-muted w-1-slot text-center">{{ $table['column'] == 'id' && $table['sort'] == 'desc' ? $data->total() - ($data->firstItem() + $key) + 1 : $data->firstItem() + $key }}</td>
                    <td>{{ $row->name }}</td>
                    <td>
                        @if($row->color)
                            <span class="{{ getBadge($row->color) }}">{{ ucwords($row->color) }}</span>
                        @else
                            <span>Unset</span>
                        @endif
                    </td>
                    {{--                                    Jika can CRUD maka munculkan tombol--}}
                    @if(getUserLevel('users') >= CAN_CRUD)
                        <td class="pe-3 w-2-slot">
                            <div class="d-flex justify-content-center">
                                @include('form.button.crud', ['url' => 'user/privilege/', 'type' => $type, 'id' => $row->id])
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