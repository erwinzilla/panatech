<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div class="me-auto">
                <h6 class="text-default">Invoice Items</h6>
                <small>Daftar Invoice</small>
            </div>
            @if($type == 'edit')
                <button type="button" class="btn btn-primary align-self-center btn-input-modal" data-bs-toggle="modal" data-bs-target="#table-modal" data-target="invoice/item/create?invoice={{ $id }}">@svg('heroicon-s-plus','icon-sm me-2') Add Invoice Item</button>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Item</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Disc *%</th>
                    <th>Grand Total</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @if($data)
                    @if($data->count() > 0)
                        @foreach($data as $row)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $row->item }}</td>
                                <td>{{ $row->desc }}</td>
                                <td>{!! getPrice($row->price) !!}</td>
                                <td>{{ $row->qty }}</td>
                                <td>{!! getPrice($row->total) !!}</td>
                                <td>{{ $row->disc }}</td>
                                <td>{!! getPrice($row->grand_total) !!}</td>
                                <td class="text-center w-2-slot">
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-warning btn-icon me-2 btn-input-modal" data-bs-toggle="modal" data-bs-target="#table-modal" data-target="invoice/item/{{ $row->id }}/edit">@svg('heroicon-s-pencil-square','icon-sm')</button>
                                        <form id="delete-{{ $row->id }}" method="post" action="{{ url('invoice/item/'.$row->id) }}" onsubmit="confirm_delete({{ $row->id }});return false;" data-bs-toggle="tooltip" data-bs-title="Delete">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-icon">@svg('heroicon-s-trash', 'icon-sm')</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="text-center text-muted">No entries found</td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td colspan="9" class="text-center text-muted">Submit first to input invoice item</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>