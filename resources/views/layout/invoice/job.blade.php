{{--    Body--}}
<div class="row mt-3">
    <div class="col-md-3">
        <h5 class="text-black mb-0">Information</h5>
        <small class="text-muted">Data ini yang nanti akan digunakan saat memberikan informasi invoice</small>
        <br><small class="text-danger">*Wajib diisi</small>
    </div>
    @if($data)
        <div id="main" class="col-md-5">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="text-default mb-0">Invoice Input</h6>
                    <small class="text-muted">Input invoice</small>
                </div>
                @if($data->paid == 0)
                    <form action="{{ url('invoice/'.$data->id) }}" method="post">
                        @method('put')
                        @csrf
                @endif
                    <div class="card-body">
                        <input type="hidden" name="id" value="{{ old('id', $data->id) }}">
                        <input type="hidden" name="target" value="job">
                        <div class="mb-3">
                            <label class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control w-75 @error('name') is-invalid @enderror" value="{{ old('name', $data->name) }}" placeholder="Masukan nama / kode invoice" validate {{ $data->paid ? 'readonly' : '' }}>
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tax Rate *%<span class="text-danger">*</span></label>
                            <input type="number" name="tax_rate" class="form-control w-50 @error('tax_rate') is-invalid @enderror" value="{{ old('tax_rate', $data->tax_rate) }}" placeholder="Tax Rate" validate {{ $data->paid ? 'readonly' : '' }}>
                            @error('tax_rate')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            @if($data->paid == 0)
                                <div class="form-check">
                                    <input name="paid" class="form-check-input" type="checkbox" value="1" id="checkboxPaid" {{ $data->paid ? 'checked' : ''}}>
                                    <label class="form-check-label" for="checkboxPaid">
                                        Already Paid?
                                    </label>
                                </div>
                            @else
                                <span class="text-default">Status: </span><span>Already Paid</span>
                            @endif
                        </div>
                    </div>
                @if($data->paid == 0)
                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            @include('form.button.submit')
                        </div>
                    </div>
                    </form>
                @endif
            </div>
        </div>
    @else
        <div id="main" class="col-md-9">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="d-flex w-100">
                        <div>
                            <h6 class="text-default mb-0">Invoice Input</h6>
                            <small class="text-muted">Silahkan generate invoice untuk membuka semua data</small>
                        </div>
                        <a href="{{ url('invoice/create/job/'.$job_id) }}" class="btn btn-primary align-self-center ms-auto">Generate Invoice</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if($data)
        <div class="col-md-4 pb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="text-default mb-0">Detail Invoice</h6>
                    <small>Keterangan penjumlahan invoice</small>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <tr>
                            <td class="text-default">Sub Total</td>
                            <td>:</td>
                            <td>
                                @if($data->sub_total > 0)
                                    {!! getPrice($data->sub_total) !!}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-default">Tax Rate</td>
                            <td>:</td>
                            <td>{{ $data->tax_rate }}%</td>
                        </tr>
                        <tr>
                            <td class="text-default">Tax Amount</td>
                            <td>:</td>
                            <td>
                                @if($data->tax_amount > 0)
                                    {!! getPrice($data->tax_amount) !!}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-primary">Grand Total</td>
                            <td>:</td>
                            <td>
                                @if($data->grand_total > 0)
                                    {!! getPrice($data->grand_total) !!}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    @endif
    <div class="col-md-12">
        @include('layout.invoice.item.show', ['data' => $data ? $data_additional : null, 'id' => $data ? $data->id : null, 'paid' => $data ? $data->paid : false, 'type' => $data ? 'edit' : 'create'])
    </div>
</div>