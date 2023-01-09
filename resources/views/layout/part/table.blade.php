@include('component.table.header', ['perPage' => $table['perPage'], 'search' => $table['search']])
<div class="table-container table-responsive">
    <table class="table table-striped mb-0 data-table align-middle">
        <thead>
        <tr>
            @include('component.table.title', ['title' => '#', 'column' => 'id', 'sortable' => true, 'class' => 'text-center'])
            @include('component.table.title', ['title' => 'SKU', 'column' => 'sku', 'sortable' => true])
            @include('component.table.title', ['title' => 'Name', 'column' => 'name', 'sortable' => true])
            @include('component.table.title', ['title' => 'Price', 'column' => 'price', 'sortable' => true])
            @include('component.table.title', ['title' => 'Loc', 'column' => 'loc', 'sortable' => true])
            {{-- Jika can CRUD maka munculkan tombol--}}
            @if(getUserLevel($config['privilege']) >= CAN_CRUD && ($type == 'data' || $type == 'trash'))
                @include('component.table.title', ['title' => 'Action', 'column' => 'action', 'sortable'=> false, 'class' => 'text-center align-middle'])
            @endif
            @if($type == 'choose')
                @include('component.table.title', ['title' => 'Action', 'column' => 'action', 'sortable'=> false, 'class' => 'text-center align-middle px-4'])
            @endif
        </tr>
        </thead>
        <tbody>
        @if($data->total() == 0)
            <tr>
                @php
                    $colspan = 5;
                    if (getUserLevel($config['privilege']) >= CAN_CRUD) {
                        $colspan += 1; // ada baguian untuk action button
                    }
                @endphp
                <td class="text-muted text-center" colspan="{{ $colspan }}">No entries found</td>
            </tr>
        @else
            @foreach($data as $key => $row)
                <tr>
                    <td class="text-center text-muted w-1-slot">{{ $table['column'] == 'id' && $table['sort'] == 'desc' ? $data->total() - ($data->firstItem() + $key) + 1 : $data->firstItem() + $key }}</td>
                    <td>
                        <span>{{ $row->sku }}</span>
                    </td>
                    <td>
                        <span>{{ $row->name }}</span>
                    </td>
                    <td>
                        {!! getPrice($row->price) !!}
                    </td>
                    <td>
                        @if($row->loc)
                            <span class="text-muted">{{ $row->loc }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    {{--                                    Jika can CRUD maka munculkan tombol--}}
                    @if(getUserLevel($config['privilege']) >= CAN_CRUD && ($type == 'data' || $type == 'trash'))
                        <td class="pe-3 w-2-slot">
                            <div class="d-flex">
                                @include('form.button.crud', ['url' => $config['url'].'/', 'type' => $type, 'id' => $row->id])
                            </div>
                        </td>
                    @endif
                    @if($type == 'choose')
                        <td class="w-1-slot text-center">
                            <button type="button" class="btn btn-success btn-icon" data-bs-toggle="tooltip" data-bs-title="Choose" onclick="choose('part', {{ $row->id }}, 'table');return false;" data-bs-dismiss="modal">
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