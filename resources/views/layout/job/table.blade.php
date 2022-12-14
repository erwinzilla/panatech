@include('component.table.header', ['perPage' => $table['perPage'], 'search' => $table['search']])
<div class="d-flex w-100 justify-content-between px-3">
    <div class="table-filter d-flex h-100">
        <div class="dropdown me-2">
            <button type="button" class="btn btn-info dropdown-toggle h-100" data-bs-toggle="dropdown" aria-expanded="false">@svg('heroicon-s-funnel', 'icon-sm me-1') Filter Data</button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ url($config['url'].'?filter[]=1&filter[]=2&filter[]=9&filter[]=10&target=table') }}">Only On-Handled Job</a></li>
                <li><a class="dropdown-item" href="{{ url($config['url'].'?filter[]=1&filter[]=2&target=table') }}">Hide Book-in and Pending</a></li>
                <li><a class="dropdown-item" href="{{ url($config['url'].'?filter[]=9&filter[]=10&target=table') }}">Hide Invoice and Cancelled</a></li>
            </ul>
        </div>
        <a href="{{ url('job/generate/label') }}" target="_blank" class="btn btn-icon btn-secondary" data-bs-toggle="tooltip" data-bs-title="Generate QC Label">
            @svg('heroicon-s-printer', 'icon-sm')
        </a>
    </div>
    <div class="table-update-date d-inline-flex w-50">
        <label class="align-self-center w-100 me-2 text-end">Last Update</label>
        @php
        if ($data_additional->count() > 0) {
            $job_update_at = date('d/m/Y', strtotime($data_additional->first()->job_update_at));
            $id = $data_additional->first()->id;
        } else {
            $job_update_at = date('d/m/Y');
            $id = null;
        }
        @endphp
        @if($id)
            <input type="text" name="job_update_at" class="form-control w-50" value="{{ $job_update_at }}" placeholder="Masukan tanggal" data-id="{{ $id }}" date-picker>
        @else
            <a href="{{ url('config') }}" class="btn btn-warning w-50">@svg('heroicon-s-exclamation-triangle', 'icon me-2') Set Config First</a>
        @endif
    </div>
</div>
<div class="table-container table-responsive">
    <table class="table table-striped mb-0 data-table align-top">
        <thead>
        <tr>
            @include('component.table.title', ['title' => '#', 'column' => 'jobs.id', 'sortable' => true, 'class' => 'text-center'])
            @include('component.table.title', ['title' => 'Created', 'column' => 'jobs.created_at', 'sortable' => true])
            @include('component.table.title', ['title' => 'No. Job', 'column' => 'jobs.name', 'sortable' => true])
            @include('component.table.title', ['title' => 'No. Inv', 'column' => 'jobs.invoice_name', 'sortable' => true])
            @include('component.table.title', ['title' => 'Customer', 'column' => 'jobs.customer_name', 'sortable' => true])
            @include('component.table.title', ['title' => 'Product / Warranty', 'column' => 'jobs.model', 'sortable' => true, 'class' => 'text-nowrap'])
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
                        <small class="d-inline-flex {{ $row->quality_report ? 'text-secondary' : '' }} {{ $row->dealer_report ? 'text-primary' : '' }} {{ $row->states->name == 'Cancelled' ? 'text-muted' : '' }}">{{ date('l, d/m/Y H:i', strtotime($row->created_at)) }}</small>
                        @if($row->quality_report)
                            <br><span class="{{ getBadge('secondary') }}">QC Pass</span>
                        @endif
                        @if($row->quality_label)
                            <br><span class="{{ getBadge('gray') }}">QC Label</span>
                        @endif
                        @if($row->dealer_report)
                            <br><span class="{{ getBadge('blue') }}">Dealer Checked</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ url($config['url'].'/'.$row->id.'/edit') }}" target="_blank" class="text-nowrap {{ $row->quality_report ? 'text-secondary' : '' }} {{ $row->dealer_report ? 'text-primary' : '' }}  {{ $row->states->name == 'Cancelled' ? 'text-muted' : '' }}">
                            <span>{{ $row->name }}</span>
                        </a>
                        @if($row->ticket)
                            <br><small class="text-muted">{{ $row->tickets->name }}</small>
                        @endif
                        @if($row->branch_service)
                            <br><span class="{{ getBadge('primary') }}">{{ $row->branch_services->code }}</span>
                        @endif
                    </td>
                    <td>
                        @if($row->invoice)
                            <a href="{{ url('invoice/'.$row->invoice.'/edit') }}" target="_blank" class="text-nowrap {{ $row->quality_report ? 'text-secondary' : '' }} {{ $row->dealer_report ? 'text-primary' : '' }}  {{ $row->states->name == 'Cancelled' ? 'text-muted' : '' }}">
                                <span>{{ $row->invoice_name }}</span>
                            </a>
                            @if($row->invoice_paid)
                                <br><span class="{{ getBadge('primary') }}">Paid</span>
                            @endif
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="{{ $row->quality_report ? 'text-secondary' : '' }} {{ $row->dealer_report ? 'text-primary' : '' }}  {{ $row->states->name == 'Cancelled' ? 'text-muted' : '' }}">{{ $row->customer_name }}</span>
                        <br><small class="{{ $row->quality_report ? 'text-secondary' : 'text-muted' }} {{ $row->dealer_report ? 'text-primary' : 'text-muted' }}  {{ $row->states->name == 'Cancelled' ? 'text-muted' : 'text-muted' }}">{{ $row->phone }}</small>
                        @if($row->phone2)
                            <br><small class="text-muted">{{ $row->phone2 }}</small>
                        @endif
                        @if($row->phone3)
                            <br><small class="text-muted">{{ $row->phone3 }}</small>
                        @endif
                        @if(strlen($row->address) > 30)
                            <br><small class="text-muted mb-0 text-nowrap" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $row->address }}">{{ substr_replace($row->address, '...', 30) }}</small>
                        @else
                            <br><small class="text-muted mb-0 text-nowrap">{{ $row->address }}</small>
                        @endif
                        @if($row->email)
                            <br><small><a href="mailto:{{ $row->email }}">{{ $row->email }}</a></small>
                        @endif
                        @if($row->customer_type)
                            <br><small class="text-primary">{{ $row->customer_types->name }}</small>
                        @endif
                    </td>
                    <td>
                        <span class="{{ $row->quality_report ? 'text-secondary' : '' }} {{ $row->dealer_report ? 'text-primary' : '' }}  {{ $row->states->name == 'Cancelled' ? 'text-muted' : '' }}">{{ $row->model }}</span>
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
                        @if(strlen($row->service_info) > 30)
                            <span class="text-nowrap {{ $row->quality_report ? 'text-secondary' : '' }} {{ $row->dealer_report ? 'text-primary' : '' }}  {{ $row->states->name == 'Cancelled' ? 'text-muted' : '' }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $row->service_info }}">{{ substr_replace($row->service_info, '...', 30) }}</span>
                        @else
                            <span class="text-nowrap {{ $row->quality_report ? 'text-secondary' : '' }} {{ $row->dealer_report ? 'text-primary' : '' }}  {{ $row->states->name == 'Cancelled' ? 'text-muted' : '' }}">{{ $row->service_info }}</span>
                        @endif
                        @if(strlen($row->repair_info) > 30)
                            <br><small class="text-muted text-nowrap" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $row->repair_info }}">{{ substr_replace($row->repair_info, '...', 30) }}</small>
                        @else
                            <br><small class="text-muted text-nowrap">{{ $row->repair_info }}</small>
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
                                @if($type == 'data')
                                    <a href="http://wa.me/62{{ substr($row->phone, 1) }}" target="_blank" class="btn btn-teal btn-icon me-2" data-bs-toggle="tooltip" data-bs-title="Send WhatsApp">@svg('heroicon-o-chat-bubble-oval-left', 'icon-sm')</a>
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

@section('script')
    <script>
        let datePicker = $('.datepicker')
        if (datePicker) {
            let cell = datePicker.querySelectorAll('.datepicker-cell');
            cell.forEach((el) => {
                el.addEventListener('click', () => {
                    console.log('work')
                })
            })
        }
    </script>
@endsection