@extends('layout.template')

@section('title', ucwords($title))

@section('sidebar')
    @include($config['blade'].'.sidebar')
@endsection

@section('content')
    @include('component.breadcrumb')

    {{--    Body--}}
    <div class="row mt-3">
        <div class="col-md-3">
            <h5 class="text-black mb-0">Information</h5>
            <small class="text-muted">Data ini yang nanti akan digunakan saat memberikan informasi invoice</small>
            <br><small class="text-danger">*Wajib diisi</small>
        </div>
        <div id="main" class="col-md-5">
            <div class="card mb-3">
                @include('form.header.start')
                <div class="card-body">
                    <input type="hidden" name="id" value="{{ old('id', $data->id) }}">
                    <div class="mb-3">
                        <label class="form-label">Name<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control w-75 @error('name') is-invalid @enderror" value="{{ old('name', $data->name) }}" placeholder="Masukan nama / kode invoice" validate>
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tax Rate *%<span class="text-danger">*</span></label>
                        <input type="number" name="tax_rate" class="form-control w-50 @error('tax_rate') is-invalid @enderror" value="{{ old('tax_rate', $data->tax_rate) }}" placeholder="Tax Rate" validate>
                        @error('tax_rate')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input name="paid" class="form-check-input" type="checkbox" value="1" id="checkboxPaid" {{ $data->paid ? 'checked' : ''}}>
                            <label class="form-check-label" for="checkboxPaid">
                                Already Paid?
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        @include('form.button.submit')
                    </div>
                </div>
                @include('form.header.end')
            </div>
        </div>
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
                            <td class="text-default">Grand Total</td>
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
        <div class="col-md-12">
            @include('layout.invoice.item.show', ['data' => $data_additional, 'id' => $data->id])
        </div>
    </div>

    @include('component.modal.table')
@endsection

@section('script')
    <script>
        initInput('{{ $config['url'] }}');
    </script>
@endsection