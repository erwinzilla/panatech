@extends('layout.template')

@section('title', ucwords($title))

@section('sidebar')
    @include($config['blade'].'.sidebar')
@endsection

@section('content')
    @include('component.breadcrumb')

    @include('form.header.start')
    {{--    Body--}}
    <div class="row mt-3">
        <div class="col-md-3">
            <h5 class="text-black mb-0">Information</h5>
            <small class="text-muted">Data ini yang nanti akan digunakan saat memberikan informasi warranty</small>
            <br><small class="text-danger">*Wajib diisi</small>
        </div>
        <div id="main" class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Type<span class="text-danger">*</span></label>
                        <select name="type" class="form-select w-25 @error('type') is-invalid @enderror">
                            <option value="0" {{ old('type', $data->type) == 0 ? 'selected' : '' }}>Out</option>
                            <option value="1" {{ old('type', $data->type) == 1 ? 'selected' : '' }}>In</option>
                        </select>
                        @error('type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Model<span class="text-danger">*</span></label>
                        <input type="text" name="model" class="form-control w-50 @error('model') is-invalid @enderror" value="{{ old('model', $data->model) }}" placeholder="Masukan model">
                        @error('model')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Serial<span class="text-danger">*</span></label>
                        <input type="text" name="serial" class="form-control w-50 @error('serial') is-invalid @enderror" value="{{ old('serial', $data->serial) }}" placeholder="Masukan nomor seri">
                        @error('serial')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="collapse {{ $data->type == 1 ? 'show' : '' }}" id="collapseIn">
                        <div class="mb-3">
                            <label class="form-label">No. Warranty<span class="text-danger">*</span></label>
                            <input type="text" name="warranty_no" class="form-control w-50 @error('warranty_no') is-invalid @enderror" value="{{ old('warranty_no', $data->warranty_no) }}" placeholder="Masukan nomor garansi">
                            @error('warranty_no')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Purchase Date<span class="text-danger">*</span></label>
                            <input type="date" name="purchase_date" class="form-control w-50 @error('purchase_date') is-invalid @enderror" value="{{ date('Y-m-d', strtotime(old('purchase_date', $data->purchase_date))) }}" placeholder="Masukan nomor garansi">
                            @error('purchase_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Customer</label>
                        <input type="hidden" name="customer" value="{{ old('customer', $data->customer) }}">
                        <div class="d-flex justify-content-start">
                            <button type="button" class="btn {{ $data->customer ? 'btn-warning' : 'btn-primary' }} me-3 btn-table-modal" data-bs-toggle="modal" data-bs-target="#table-modal" data-target="customer">@svg('heroicon-s-user', 'icon') {{ $data->customer ? 'Edit' : 'Select' }} Customer</button>
                            <div id="customer-data">
                                @if($data->customer)
                                    @include('layout.customer.show', ['data' => $data->customers])
                                @else
                                    <span>Select customer first</span>
                                @endif
                            </div>
                        </div>
                        @error('customer')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        @include('form.button.submit')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('form.header.end')

    @include('component.modal.table')
@endsection

@section('script')
    <script>
        const bsCollapse = new bootstrap.Collapse('#collapseIn', {
            toggle: false
        });

        let selectType = $('select[name="type"]');
        selectType.addEventListener('change', function () {
            if (selectType.value === '1') {
                bsCollapse.show();
            } else {
                bsCollapse.hide();
            }
        });
    </script>
@endsection