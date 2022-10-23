@include('component.table.header', ['perPage' => $table['perPage'], 'search' => $table['search']])
<div class="table-container table-responsive">
    <table class="table table-striped mb-0 data-table align-middle">
        <thead>
        <tr>
            @include('component.table.title', ['title' => '#', 'column' => 'tickets.id', 'sortable' => true, 'class' => 'text-center'])
            @include('component.table.title', ['title' => 'Name', 'column' => 'tickets.type', 'sortable' => true])
            @include('component.table.title', ['title' => 'Customer', 'column' => 'tickets.customer_name', 'sortable' => true])
            @include('component.table.title', ['title' => 'Product / Warranty', 'column' => 'tickets.model', 'sortable' => true])
            @include('component.table.title', ['title' => 'Service Info', 'column' => 'tickets.service_info', 'sortable' => true])
            @include('component.table.title', ['title' => 'Status', 'column' => 'states.name', 'sortable' => true])
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
                        {{ $row->name }}
                        @if($row->branch_service)
                            <br><span class="{{ getBadge('primary') }}">{{ $row->branch_services->code }}</span>
                        @endif
                    </td>
                    <td>
                        <span>{{ $row->customer_name }}</span>
                        <br><span>{{ $row->phone }}</span>
                        @if($row->phone2)
                            <br><span class="text-muted">{{ $row->phone2 }}</span>
                        @endif
                        @if($row->phone3)
                            <br><span class="text-muted">{{ $row->phone3 }}</span>
                        @endif
                        <br><small class="text-muted mb-0">{{ $row->address }}</small>
                        @if($row->email)
                            <br><small><a href="mailto:{{ $row->email }}">{{ $row->email }}</a></small>
                        @endif
                        @if($row->customer_type)
                            <br><small class="text-primary">{{ $row->customer_types->name }}</small>
                        @endif
                    </td>
                    <td>
                        <span>{{ $row->model }}</span>
                        @if($row->serial)
                            <br><small class="text-muted">{{ $row->serial }}</small>
                        @endif
                        @if($row->warranty_no)
                            <br><small class="text-primary">{{ $row->warranty_no }}</small>
                        @endif
                        @if($row->warranty_type == OUT_WARRANTY)
                            <br><small class="text-muted">Out-Warranty</small>
                        @endif
                        @if($row->warranty_type == IN_WARRANTY)
                            <br><small class="text-primary">In-Warranty</small>
                        @endif
                    </td>
                    <td>{{ $row->service_info }}</td>
                    <td>
                        @if($row->status)
                            <span class="{{ getBadge($row->states->color) }}">{{ $row->states->name }}</span>
                        @endif
                    </td>
                    {{--                                    Jika can CRUD maka munculkan tombol--}}
                    @if(getUserLevel($config['privilege']) >= CAN_CRUD)
                        <td class="pe-3 w-2-slot">
                            <div class="d-flex">
                                @include('form.button.crud', ['url' => $config['url'].'/', 'type' => $type, 'id' => $row->id])
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