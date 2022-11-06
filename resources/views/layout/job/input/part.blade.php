<div class="row mt-3">
    @if(!$data->on_invoice)
        <div class="col-md-3">
            <h5 class="text-black mb-0">Search Part</h5>
            <small class="text-muted">Cari part berdasarkan data</small>
        </div>
        <div id="main" class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
                    <input type="hidden" name="job" value="{{ old('id', $data->id) }}">
                    <div class="d-flex justify-content-between w-100">
                        <button id="find-part" type="button" class="btn btn-primary btn-table-modal" data-bs-toggle="modal" data-bs-target="#table-modal" data-target="part">@svg('heroicon-s-magnifying-glass', 'icon-sm me-2') Cari Part</button>
                        <button id="submit-part" type="button" class="btn btn-success" onclick="sendPart({{$data->id}});return false;">@svg('heroicon-s-paper-airplane', 'icon-sm me-2') Submit Parts</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body p-0">
                <div id="table-data-part">
                    <div class="table-container table-responsive">
                        <table class="table table-striped mb-0 data-table align-middle">
                            <thead>
                            <tr>
                                @include('component.table.title', ['title' => '#', 'column' => 'id', 'sortable' => false, 'class' => 'text-center'])
                                @include('component.table.title', ['title' => 'Part No.', 'column' => 'sku', 'sortable' => false])
                                @include('component.table.title', ['title' => 'Description', 'column' => 'name', 'sortable' => false])
                                @include('component.table.title', ['title' => 'Price', 'column' => 'price', 'sortable' => false])
                                @include('component.table.title', ['title' => 'Qty', 'column' => 'qty', 'sortable' => false])
                                @include('component.table.title', ['title' => 'Total', 'column' => 'total', 'sortable' => false])
                                @if(!$data->on_invoice)
                                    @include('component.table.title', ['title' => 'Action', 'column' => 'action', 'sortable' => false])
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                                @if($data_additional['job_part']->count() > 0)
                                    @foreach($data_additional['job_part'] as $row)
                                        <tr>
                                            <td class="text-center num">{{ $loop->iteration }}</td>
                                            <td id="sku-{{ $row->sku }}" data-name="sku">{{ $row->sku }}</td>
                                            <td data-name="name">{{ $row->name }}</td>
                                            <td id="price-{{ $row->sku }}" data-name="price">{{ $row->price }}</td>
                                            <td id="qty-{{ $row->sku }}" data-name="qty">{{ $row->qty }}</td>
                                            <td id="total-{{ $row->sku }}">{{ $row->price * $row->qty }}</td>
                                            @if(!$data->on_invoice)
                                                <td class="pe-3 w-2-slot">
                                                    <div class="d-flex">
                                                        <button type="button" class="btn btn-warning btn-icon me-2 edit" data-bs-toggle="tooltip" data-bs-title="Edit" data-id="{{ $row->sku }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon-sm">
                                                                <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                                                                <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
                                                            </svg>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-icon delete" data-bs-toggle="tooltip" data-bs-title="Delete" data-id="{{ $row->sku }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon-sm">
                                                                <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="no-entries">
                                        <td colspan="7" class="text-muted text-center">Silahkan Masukan Part</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <span class="text-default">Status: </span><span id="table-status" class="text-muted">{{ !$data->on_invoice ? 'Idle' : "Can't edit status job on invoice" }}</span>
            </div>
        </div>
    </div>
</div>