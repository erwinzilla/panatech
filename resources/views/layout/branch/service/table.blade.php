@include('component.table.header', ['perPage' => $table['perPage'], 'search' => $table['search']])
<div class="table-container table-responsive">
    <table class="table table-striped mb-0 data-table align-middle">
        <thead>
        <tr>
            @include('component.table.title', ['title' => '#', 'column' => 'id', 'sortable' => true, 'class' => 'text-center'])
            @include('component.table.title', ['title' => 'Branch', 'column' => 'branch', 'sortable' => true])
            @include('component.table.title', ['title' => 'Service Center', 'column' => 'name', 'sortable' => true])
            @include('component.table.title', ['title' => 'Coordinator', 'column' => 'branch_coordinator', 'sortable' => true])
            @include('component.table.title', ['title' => 'Code', 'column' => 'code', 'sortable' => true])
            @include('component.table.title', ['title' => 'Head Service', 'column' => 'user', 'sortable' => true])
            @include('component.table.title', ['title' => 'Address', 'column' => 'address', 'sortable' => true])
            @include('component.table.title', ['title' => 'Phone', 'column' => 'phone', 'sortable' => true])
            @include('component.table.title', ['title' => 'Fax', 'column' => 'fax', 'sortable' => true])
            @include('component.table.title', ['title' => 'Email', 'column' => 'email', 'sortable' => true])
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
                    $colspan = 10;
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
                        @if($row->branch)
                            {{ ucwords($row->branches->name) }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $row->name }}</td>
                    <td>
                        @if($row->branch_coordinator)
                            {{ ucwords($row->branch_coordinators->name) }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $row->code }}</td>
                    <td>
                        @if($row->user)
                            <span>{{ ucwords($row->users->name) }}</span>
                            <br><small>{{ $row->users->phone }}</small>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <address>{{ $row->address ?: '-' }}</address>
                    </td>
                    <td>
                        <span>{{ $row->phone ?: '-' }}</span>
                    </td>
                    <td>
                        <span>{{ $row->fax ?: '-' }}</span>
                    </td>
                    <td>
                        <span>
                            {{ $row->email ?: '-' }}
                            @if($row->user)
                                <br>{{ $row->users->email }}
                            @endif
                        </span>
                    </td>
                    {{--                                    Jika can CRUD maka munculkan tombol--}}
                    @if(getUserLevel('branches') >= CAN_CRUD)
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