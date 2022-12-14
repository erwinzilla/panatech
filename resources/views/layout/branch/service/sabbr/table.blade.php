@include('component.table.header', ['perPage' => $table['perPage'], 'search' => $table['search']])
<div class="table-container table-responsive">
    <table class="table table-striped mb-0 data-table align-middle">
        <thead>
        <tr>
            @include('component.table.title', ['title' => '#', 'column' => 'branch_services.id', 'sortable' => true, 'class' => 'text-center'])
            @include('component.table.title', ['title' => 'Service Center', 'column' => 'branch_services.name', 'sortable' => true])
            @include('component.table.title', ['title' => 'Code', 'column' => 'branch_services.code', 'sortable' => true])
            @include('component.table.title', ['title' => 'Head Service', 'column' => 'users.name', 'sortable' => true])
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
                    <td>{{ $row->code }}</td>
                    <td>
                        @if($row->user)
                            <span>{{ ucwords($row->users->name) }}</span>
                            <br><small class="text-muted">{{ $row->users->phone }}</small>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    {{--                                    Jika can CRUD maka munculkan tombol--}}
                    @if(getUserLevel($config['privilege']) >= CAN_CRUD)
                        @if($row->have_sabbr > 0)
                            <td class="pe-3 w-2-slot">
                                <div class="d-flex">
                                    @include('form.button.crud', ['url' => $config['url'].'/', 'type' => $type, 'id' => $row->id])
                                </div>
                            </td>
                        @else
                            <td class="w-1-slot">
                                <a href="{{ url($config['url'].'/create/'.$row->id) }}" class="btn btn-primary btn-icon me-2" data-bs-toggle="tooltip" data-bs-title="Generate SABBR">@svg('heroicon-s-chart-bar', 'icon-sm')</a>
                            </td>
                        @endif
                    @endif
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
@include('component.table.footer', ['total' => $data->total(), 'firstItem' => $data->firstItem(), 'lastItem' => $data->lastItem(), 'data' => $data])