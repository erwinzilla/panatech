@include('component.table.header', ['perPage' => $table['perPage'], 'search' => $table['search']])
<div class="table-container table-responsive">
    <table class="table table-striped mb-0 data-table align-middle">
        <thead>
        <tr>
            @include('component.table.title', ['title' => '#', 'column' => 'customers.id', 'sortable' => true, 'class' => 'text-center'])
            @include('component.table.title', ['title' => $type == 'choose' ? 'Name / Detail' : 'Name', 'column' => 'customers.name', 'sortable' => true])
            @if($type == 'data' || $type == 'trash')
                @include('component.table.title', ['title' => 'Phone', 'column' => 'customers.phone', 'sortable' => true])
                @include('component.table.title', ['title' => 'Address', 'column' => 'customers.address', 'sortable' => true])
            @endif
            @include('component.table.title', ['title' => 'Email', 'column' => 'customers.email', 'sortable' => true])
            @include('component.table.title', ['title' => 'Type', 'column' => 'customer_types.name', 'sortable' => true])
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
                    $colspan = 6;
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
                    <td>
                        @if($type == 'choose')
                            <span>{{ $row->name }}</span>
                            <br><span class="text-muted">{{ $row->phone }}</span>
                            @if($row->phone2)
                                <br><span class="text-muted">{{ $row->phone2 }}</span>
                            @endif
                            @if($row->phone3)
                                <br><span class="text-muted">{{ $row->phone3 }}</span>
                            @endif
                            @if($row->address)
                                <br><address class="text-muted mb-0">{{ $row->address }}</address>
                            @endif
                        @else
                            <span>{{ $row->name }}</span>
                            @if($row->tax_id)
                                <span class="text-muted">{{ $row->tax_id }}</span>
                            @endif
                        @endif
                    </td>
                    @if($type == 'data' || $type == 'trash')
                        <td>
                            {{ $row->phone }}
                            @if($row->phone2)
                                <br>{{ $row->phone2 }}
                            @endif
                            @if($row->phone3)
                                <br>{{ $row->phone3 }}
                            @endif
                        </td>
                        <td>
                            <address class="mb-0">{{ $row->address ?: '-' }}</address>
                        </td>
                    @endif
                    <td>
                        @if($row->email)
                            <a href="mailto:{{ $row->email }}">{{ $row->email }}</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($row->type)
                            <span>{{ ucwords($row->types->name) }}</span>
                        @else
                            <span>-</span>
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
                            <button type="button" class="btn btn-success btn-icon" data-bs-toggle="tooltip" data-bs-title="Choose" onclick="choose('customer', {{ $row->id }}, '{{ $row->name }}', null, null);return false;" data-bs-dismiss="modal">
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