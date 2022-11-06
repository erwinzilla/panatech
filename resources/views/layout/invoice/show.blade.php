<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div class="me-auto">
                <h6 class="text-default">Invoice Items</h6>
                <small>Daftar Invoice</small>
            </div>
            @if($type == 'edit')
                <button type="button" class="btn btn-primary align-self-center">@svg('heroicon-s-plus','icon-sm me-2') Add Invoice Item</button>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Disc *%</th>
                    <th>Grand Total</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @if($data)
                    @if($data->count() > 0)
                        @foreach($data as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->item }}</td>
                                <td>{{ $row->desc }}</td>
                                <td>{{ $row->price }}</td>
                                <td>{{ $row->qty }}</td>
                                <td>{{ $row->total }}</td>
                                <td>{{ $row->disc }}</td>
                                <td>{{ $row->grand_total }}</td>
                                <td>

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