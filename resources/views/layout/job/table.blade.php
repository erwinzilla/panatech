@include('component.table.header', ['perPage' => $table['perPage'], 'search' => $table['search']])
<div class="d-flex w-100 justify-content-between px-3">
    <div class="table-filter h-100">
        <div class="dropdown">
            <button type="button" class="btn btn-info dropdown-toggle h-100" data-bs-toggle="dropdown" aria-expanded="false">@svg('heroicon-s-funnel', 'icon-sm me-1') Filter Data</button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ url($config['url'].'?filter[]=1&filter[]=2&filter[]=9&filter[]=10&target=table') }}">Only On-Handled Job</a></li>
                <li><a class="dropdown-item" href="{{ url($config['url'].'?filter[]=1&filter[]=2&target=table') }}">Hide Book-in and Pending</a></li>
                <li><a class="dropdown-item" href="{{ url($config['url'].'?filter[]=9&filter[]=10&target=table') }}">Hide Invoice and Cancelled</a></li>
            </ul>
        </div>
    </div>
    <div class="table-update-date d-inline-flex">
        <label class="align-self-center w-100 me-2">Last Update</label>
        <input type="date" name="job_update_at" class="form-control @error('job_update_at') is-invalid @enderror" value="{{ date('Y-m-d') }}" placeholder="Masukan tanggal pembuatan" validate>
    </div>
</div>
<div class="table-container table-responsive">
    <table class="table table-striped mb-0 data-table align-middle">
        <thead>
        <tr>
            @include('component.table.title', ['title' => '#', 'column' => 'jobs.id', 'sortable' => true, 'class' => 'text-center'])
            @include('component.table.title', ['title' => 'No. Job', 'column' => 'jobs.name', 'sortable' => true])
            @include('component.table.title', ['title' => 'No. Inv', 'column' => 'jobs.invoice_name', 'sortable' => true])
            @include('component.table.title', ['title' => 'Customer', 'column' => 'jobs.customer_name', 'sortable' => true])
            @include('component.table.title', ['title' => 'Product / Warranty', 'column' => 'jobs.model', 'sortable' => true])
            @include('component.table.title', ['title' => 'Service Info', 'column' => 'jobs.service_info', 'sortable' => true])
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
                    $colspan = 9;
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
                        @if($row->ticket)
                            <br><small class="text-muted">{{ $row->tickets->name }}</small>
                        @endif
                        @if($row->branch_service)
                            <br><span class="{{ getBadge('primary') }}">{{ $row->branch_services->code }}</span>
                        @endif
                        <small class="d-inline-flex text-info mt-1">@svg('heroicon-s-calendar-days', 'icon-sm me-1',['style' => 'margin-top:1px;']) {{ date('d/m/Y', strtotime($row->created_at)) }}</small>
                    </td>
                    <td>
                        @if($row->invoice_name)
                            <span>{{ $row->invoice_name }}</span>
                        @else
                            <span class="text-muted">-</span>
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
                    <td>
                        {{ $row->service_info }}
                        @if($row->repair_info)
                            <br><span class="text-success">{{ $row->repair_info }}</span>
                        @endif
                    </td>
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