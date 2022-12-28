@include('component.table.header', ['perPage' => $table['perPage'], 'search' => $table['search']])
<div class="table-container table-responsive">
    <table class="table table-striped mb-0 data-table align-middle">
        <thead>
        <tr>
            @include('component.table.title', ['title' => '#', 'column' => 'warranties.id', 'sortable' => true, 'class' => 'text-center'])
            @include('component.table.title', ['title' => 'Customer', 'column' => 'customers.name', 'sortable' => true])
            @include('component.table.title', ['title' => 'Type', 'column' => 'warranties.type', 'sortable' => true])
            @include('component.table.title', ['title' => 'Model', 'column' => 'warranties.model', 'sortable' => true])
            @include('component.table.title', ['title' => 'Serial', 'column' => 'warranties.serial', 'sortable' => true])
            @include('component.table.title', ['title' => 'No. Warranty', 'column' => 'warranties.warranty_no', 'sortable' => true])
            @include('component.table.title', ['title' => 'Purchase Date', 'column' => 'warranties.purchase_date', 'sortable' => true])
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
                    $colspan = 7;
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
                        @if($row->customer)
                            <span>{{ ucwords($row->customers->name) }}</span>
                            <br><small class="text-muted">{{ $row->customers->phone }}</small>
                            @if($row->customers->phone2)
                                <br><small class="text-muted">{{ $row->customers->phone2 }}</small>
                            @endif
                            @if($row->customers->phone3)
                                <br><small class="text-muted">{{ $row->customers->phone3 }}</small>
                            @endif
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        {{ $row->type == 0 ? 'Out' : 'In' }}
                    </td>
                    <td class="text-nowrap">{{ $row->model }}</td>
                    <td class="text-nowrap">{{ $row->serial }}</td>
                    <td>
                        @if($row->warranty_no)
                            <span class="text-nowrap">{{ $row->warranty_no }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($row->type == IN_WARRANTY)
                            <span>{{ date('d/m/Y', strtotime($row->purchase_date)) }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    {{--                                    Jika can CRUD maka munculkan tombol--}}
                    @if(getUserLevel($config['privilege']) >= CAN_CRUD)
                        <td class="pe-3 w-2-slot">
                            <div class="d-flex">
                                @if($type == 'data')
                                    <a href="{{ url('ticket/create/'.$row->id) }}" class="btn btn-secondary btn-icon me-2" data-bs-toggle="tooltip" data-bs-title="Generate Ticket">@svg('heroicon-s-ticket', 'icon-sm')</a>
                                @endif
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