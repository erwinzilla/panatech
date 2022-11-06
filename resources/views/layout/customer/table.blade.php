@include('component.table.header', ['perPage' => $table['perPage'], 'search' => $table['search']])
<div class="table-container table-responsive">
    <table class="table table-striped mb-0 data-table align-middle">
        <thead>
        <tr>
            @include('component.table.title', ['title' => '#', 'column' => 'customers.id', 'sortable' => true, 'class' => 'text-center'])
            @include('component.table.title', ['title' => $type == 'choose' ? 'Name / Detail' : 'Name', 'column' => 'customers.name', 'sortable' => true])
            @include('component.table.title', ['title' => 'Phone', 'column' => 'customers.phone', 'sortable' => true])
            @include('component.table.title', ['title' => 'Address / Email', 'column' => 'customers.address', 'sortable' => true])
            @if($type == 'data' || $type == 'trash')
                @include('component.table.title', ['title' => 'Type', 'column' => 'customer_types.name', 'sortable' => true])
            @endif
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
                    <td class="text-center text-muted w-1-slot">{{ $table['column'] == 'id' && $table['sort'] == 'desc' ? $data->total() - ($data->firstItem() + $key) + 1 : $data->firstItem() + $key }}</td>
                    <td>
                        @if($type == 'choose')
                            <span>{{ $row->name }}</span>
                            @if($row->type)
                                <br><span class="{{ getBadge($row->types->color) }}">{{ ucwords($row->types->name) }}</span>
                            @endif
                        @else
                            <span>{{ $row->name }}</span>
                            @if($row->tax_id)
                                <br><small class="text-muted">{{ $row->tax_id }}</small>
                            @endif
                        @endif
                    </td>
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
                        @if($row->email)
                            <br><a href="mailto:{{ $row->email }}">{{ $row->email }}</a>
                        @endif
                    </td>
                    @if($type == 'data' || $type == 'trash')
                        <td>
                            @if($row->type)
                                <span class="{{ getBadge($row->types->color) }}">{{ ucwords($row->types->name) }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    @endif
                    {{--                                    Jika can CRUD maka munculkan tombol--}}
                    @if(getUserLevel($config['privilege']) >= CAN_CRUD && ($type == 'data' || $type == 'trash'))
                        <td class="pe-3 w-2-slot">
                            <div class="d-flex">
                                @if($type == 'data')
                                    <a href="{{ url('warranty/create/'.$row->id) }}" class="btn btn-secondary btn-icon me-2" data-bs-toggle="tooltip" data-bs-title="Generate Warranty">@svg('heroicon-s-credit-card', 'icon-sm')</a>
                                    <a href="http://wa.me/62{{ substr($row->phone, 1) }}" target="_blank" class="btn btn-teal btn-icon me-2" data-bs-toggle="tooltip" data-bs-title="Send WhatsApp">@svg('heroicon-o-chat-bubble-oval-left', 'icon-sm')</a>
                                @endif
                                @include('form.button.crud', ['url' => $config['url'].'/', 'type' => $type, 'id' => $row->id])
                            </div>
                        </td>
                    @endif
                    @if($type == 'choose')
                        <td class="w-1-slot text-center">
                            <button type="button" class="btn btn-success btn-icon" data-bs-toggle="tooltip" data-bs-title="Choose" onclick="choose('customer', {{ $row->id }});return false;" data-bs-dismiss="modal">
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