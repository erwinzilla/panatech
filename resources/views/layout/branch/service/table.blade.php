@include('component.table.header', ['perPage' => $table['perPage'], 'search' => $table['search']])
<div class="table-container table-responsive">
    <table class="table table-striped mb-0 data-table align-middle">
        <thead>
        <tr>
            @include('component.table.title', ['title' => '#', 'column' => 'branch_services.id', 'sortable' => true, 'class' => 'text-center'])
            @include('component.table.title', ['title' => 'Branch', 'column' => 'branches.name', 'sortable' => true])
            @include('component.table.title', ['title' => 'Service Center', 'column' => 'branch_services.name', 'sortable' => true])
            @include('component.table.title', ['title' => 'Coordinator', 'column' => 'branch_coordinators.name', 'sortable' => true])
            @include('component.table.title', ['title' => 'Code', 'column' => 'branch_services.code', 'sortable' => true])
            @include('component.table.title', ['title' => 'Head Service', 'column' => 'users.name', 'sortable' => true])
            @include('component.table.title', ['title' => 'Address', 'column' => 'branch_services.address', 'sortable' => true])
            @include('component.table.title', ['title' => 'Phone', 'column' => 'branch_services.phone', 'sortable' => true])
            @include('component.table.title', ['title' => 'Fax', 'column' => 'branch_services.fax', 'sortable' => true])
            @include('component.table.title', ['title' => 'Email', 'column' => 'branch_services.email', 'sortable' => true])
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
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $row->name }}</td>
                    <td>
                        @if($row->branch_coordinator)
                            {{ ucwords($row->branch_coordinators->name) }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $row->code }}</td>
                    <td>
                        @if($row->user)
                            <span>{{ ucwords($row->users->name) }}</span>
                            <br><small class="text-muted">{{ $row->users->phone }}</small>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($row->address)
                            <address class="mb-0">{{ $row->address }}</address>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($row->phone)
                            <span class="text-nowrap">{{ $row->phone }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($row->fax)
                            <span class="text-nowrap">{{ $row->fax }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if(!$row->email && !$row->user)
                            <span class="text-muted">-</span>
                        @endif
                        @if($row->email)
                            <a href="mailto:{{ $row->email }}" target="_blank" class="text-nowrap">{{ $row->email }}</a><br>
                        @endif
                        @if($row->user)
                            <a href="mailto:{{ $row->users->email }}" target="_blank" class="text-nowrap">{{ $row->users->email }}</a>
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