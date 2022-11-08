@include('form.header.start')
{{--    Body--}}
<div class="row mt-3">
    <div class="col-md-3">
        <h5 class="text-black mb-0">Information</h5>
        <small class="text-muted">Data ini yang nanti akan digunakan saat memberikan informasi job desk</small>
        <br><small class="text-danger">*Wajib diisi</small>
        <br><small class="text-primary">*Langsung isi data berdasarkan database</small>
    </div>
    <div id="main" class="col-md-9">
        <div class="card mb-3">
            <div class="card-body">
                <input type="hidden" name="id" value="{{ old('id', $data->id) }}">
                <input type="hidden" name="branch_service" value="{{ old('branch_service', $data->branch_service) }}">
                <input type="hidden" name="ticket" value="{{ old('ticket', $data->ticket) }}">
                <input type="hidden" name="handle_by" value="{{ old('handle_by', $data->handle_by) }}">
                <div class="mb-3">
                    <label class="form-label">Fast Fill<span class="text-primary">*</span></label>
                    <input type="text" name="flash-fill" class="form-control w-50" value="{{ old('ticket_name', $data->ticket_name) }}" @if($data->ticket) readonly @endif>
                    <small class="text-primary">Masukan nomor tiket</small>
                    <br><small class="text-warning">Data harus dimasukan ticket terlebih dahulu</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Job Type<span class="text-danger">*</span></label>
                    <select name="job_type" class="form-select w-25 @error('job_type') is-invalid @enderror" {{ $data->on_invoice ? 'disabled' : '' }}>
                        @foreach($data_additional['job_type'] as $row)
                            <option value="{{ $row->id }}" {{ $row->id == old('job_type', $data->job_type) ? 'selected' :'' }}>{{ $row->name }}</option>
                        @endforeach
                    </select>
                    @error('job_type')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">No. Job<span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control w-50 @error('name') is-invalid @enderror" value="{{ old('name', $data->name) }}" placeholder="Masukan nomor job" validate>
                    @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Job Status<span class="text-danger">*</span></label>
                    <select name="status" class="form-select w-25 @error('status') is-invalid @enderror" {{ $data->on_invoice ? 'disabled' : '' }}>
                        @foreach($data_additional['status'] as $row)
                            @if($row->disable_input > NONE && $type == 'create')
{{--                                jangan di render --}}
                            @else
                                <option value="{{ $row->id }}" {{ $row->id == old('status', $data->status) ? 'selected' :'' }}>{{ $row->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('status')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Note</label>
                    <textarea name="note" class="form-control w-50 @error('note') is-invalid @enderror" placeholder="Masukan catatan">{{ old('note', $data->note) }}</textarea>
                    @error('note')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <hr class="border-dashed">
    </div>
    <div class="col-md-3">
        <h5 class="text-black mb-0">Customer Information</h5>
        <small class="text-muted">Data konsumen yang ingin dikunjungi</small>
    </div>
    <div id="customer" class="col-md-9">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Customer Name<span class="text-danger">*</span></label>
                    <input type="text" name="customer_name" class="form-control w-50 @error('customer_name') is-invalid @enderror" value="{{ old('customer_name', $data->customer_name) }}" placeholder="Masukan nama konsumen" validate>
                    @error('customer_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone<span class="text-danger">*</span></label>
                    <div class="d-flex">
                        <div class="w-50">
                            <input type="number" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $data->phone) }}" placeholder="Nomor telepon" validate>
                            @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <button class="btn btn-primary ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePhone" aria-expanded="false" aria-controls="collapsePhone">@svg('heroicon-s-phone', 'icon-sm') Add Another</button>
                    </div>
                </div>
                <div class="mb-3 collapse" id="collapsePhone">
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Mobile Phone</label>
                            <input type="number" name="phone2" class="form-control @error('phone2') is-invalid @enderror" value="{{ old('phone2', $data->phone2) }}" placeholder="Boleh dikosongkan">
                            @error('phone2')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col">
                            <label class="form-label">Business Phone</label>
                            <input type="number" name="phone3" class="form-control @error('phone3') is-invalid @enderror" value="{{ old('phone3', $data->phone3) }}" placeholder="Boleh dikosongkan">
                            @error('phone3')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control w-50 @error('address') is-invalid @enderror" placeholder="Alamat Konsumen" validate>{{ old('address', $data->address) }}</textarea>
                    @error('address')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control w-50 @error('email') is-invalid @enderror" value="{{ old('email', $data->email) }}" placeholder="Email Konsumen">
                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Customer Type<span class="text-danger">*</span></label>
                    <div>
                        @foreach($data_additional['customer_type'] as $row)
                            <input type="radio" class="btn-check" name="customer_type" id="option-customer-type-{{ $row->id }}" value="{{ $row->id }}" {{ old('customer_type', $data->customer_type) == $row->id ? 'checked' : ''}}>
                            <label class="btn btn-outline-primary rounded-5" for="option-customer-type-{{ $row->id }}">{{ $row->name }}</label>
                        @endforeach
                    </div>
                    @error('type')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Tax Id</label>
                    <div class="d-flex">
                        <input type="text" name="tax_id" class="form-control w-50 @error('tax_id') is-invalid @enderror" value="{{ old('tax_id', $data->tax_id) }}" placeholder="Masukan nomor NPWP atau NIK">
                        <a id="find-tax" href="#" class="btn btn-secondary btn-icon mx-2" data-bs-toggle="tooltip" data-bs-title="Find TAX ID">@svg('heroicon-s-credit-card', 'icon-sm')</a>
                        @error('tax_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <hr class="border-dashed">
    </div>
    <div class="col-md-3">
        <h5 class="text-black mb-0">Product Information</h5>
        <small class="text-muted">Data produk yang bermasalah</small>
    </div>
    <div id="product" class="col-md-9">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Warranty Type</label>
                    <select name="warranty_type" class="form-select w-25 @error('warranty_type') is-invalid @enderror">
                        <option value="{{ OUT_WARRANTY }}" {{ old('warranty_type', $data->warranty_type) == OUT_WARRANTY ? 'selected' : '' }}>Out</option>
                        <option value="{{ IN_WARRANTY }}" {{ old('warranty_type', $data->warranty_type) == IN_WARRANTY ? 'selected' : '' }}>In</option>
                    </select>
                    @error('warranty_type')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Model<span class="text-danger">*</span></label>
                    <input type="text" name="model" class="form-control w-50 @error('model') is-invalid @enderror" value="{{ old('model', $data->model) }}" placeholder="Masukan model" validate>
                    @error('model')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">No. Serial<span class="text-danger">*</span></label>
                    <input type="text" name="serial" class="form-control w-50 @error('serial') is-invalid @enderror" value="{{ old('serial', $data->serial) }}" placeholder="Masukan nomor seri" validate>
                    @error('serial')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="collapse {{ $data->warranty_type == 1 ? 'show' : '' }}" id="collapseIn">
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
                        <input type="text" name="purchase_date" class="form-control w-50 @error('purchase_date') is-invalid @enderror" value="{{ date('d/m/Y', strtotime(old('purchase_date', $data->purchase_date))) }}" placeholder="Masukan tanggal" date-picker>
                        @error('purchase_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Accessories</label>
                    <textarea name="accessories" class="form-control w-50 @error('accessories') is-invalid @enderror" placeholder="Masukan detail apa saja yang dibawa">{{ old('accessories', $data->accessories) }}</textarea>
                    @error('accessories')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Condition</label>
                    <textarea name="condition" class="form-control w-50 @error('condition') is-invalid @enderror" placeholder="Masukan detail kondisi unit">{{ old('condition', $data->condition) }}</textarea>
                    @error('condition')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <hr class="border-dashed">
    </div>
    <div class="col-md-3">
        <h5 class="text-black mb-0">Service Information</h5>
        <small class="text-muted">Data perbaikan</small>
    </div>
    <div id="date" class="col-md-9">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Service Info<span class="text-danger">*</span></label>
                    <textarea name="service_info" class="form-control w-50 @error('service_info') is-invalid @enderror" placeholder="Masukan kendala unit" validate>{{ old('service_info', $data->service_info) }}</textarea>
                    @error('service_info')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Repair Info</label>
                    <textarea name="repair_info" class="form-control w-50 @error('repair_info') is-invalid @enderror" placeholder="Masukan hasil perbaikan unit">{{ old('repair_info', $data->repair_info) }}</textarea>
                    @error('repair_info')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <hr class="border-dashed">
    </div>
    <div class="col-md-3">
        <h5 class="text-black mb-0">Date Information</h5>
        <small class="text-muted">Tanggal pelaksanaan</small>
    </div>
    <div id="date" class="col-md-9">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label">Created Date</label>
                            <div class="row g-2">
                                <div class="col">
                                    <input type="text" name="created_at" class="form-control @error('created_at') is-invalid @enderror" value="{{ date('d/m/Y', strtotime(old('created_at', $data->created_at))) }}" placeholder="Masukan tanggal" date-picker>
                                    @error('created_at')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <input type="text" name="created_at_time" class="form-control" value="{{ date('H:i', strtotime(old('created_at', $data->created_at))) }}" placeholder="hh:mm">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Repair Date</label>
                            <div class="row g-2">
                                <div class="col">
                                    <input type="text" name="repair_at" class="form-control @error('repair_at') is-invalid @enderror" value="{{ date('d/m/Y', strtotime(old('repair_at', $data->repair_at))) }}" placeholder="Masukan tanggal" date-picker>
                                    @error('repair_at')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <input type="text" name="repair_at_time" class="form-control" value="{{ date('H:i', strtotime(old('repair_at', $data->repair_at))) }}" placeholder="hh:mm">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Collection Date</label>
                            <div class="row g-2">
                                <div class="col">
                                    <input type="text" name="collection_at" class="form-control @error('collection_at') is-invalid @enderror" value="{{ date('d/m/Y', strtotime(old('collection_at', $data->collection_at))) }}" placeholder="Masukan tanggal" date-picker>
                                    @error('collection_at')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <input type="text" name="collection_at_time" class="form-control" value="{{ date('H:i', strtotime(old('collection_at', $data->collection_at))) }}" placeholder="hh:mm">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label">Actual Start Date</label>
                            <div class="row g-2">
                                <div class="col">
                                    <input type="text" name="actual_start_at" class="form-control @error('actual_start_at') is-invalid @enderror" value="{{ date('d/m/Y', strtotime(old('actual_start_at', $data->actual_start_at))) }}" placeholder="Masukan tanggal" date-picker>
                                    @error('actual_start_at')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <input type="text" name="actual_start_at_time" class="form-control" value="{{ date('H:i', strtotime(old('actual_start_at', $data->actual_start_at))) }}" placeholder="hh:mm">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Actual End Date</label>
                            <div class="row g-2">
                                <div class="col">
                                    <input type="text" name="actual_end_at" class="form-control @error('actual_end_at') is-invalid @enderror" value="{{ date('d/m/Y', strtotime(old('actual_end_at', $data->actual_end_at))) }}" placeholder="Masukan tanggal" date-picker>
                                    @error('actual_end_at')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <input type="text" name="actual_end_at_time" class="form-control" value="{{ date('H:i', strtotime(old('actual_end_at', $data->actual_end_at))) }}" placeholder="hh:mm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <hr class="border-dashed">
    </div>
    <div class="col-md-3">
        <h5 class="text-black mb-0">Labour & Transport</h5>
        <small class="text-muted">Ongkos kerja dan traport</small>
    </div>
    <div id="labour" class="col-md-9">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Labour</label>
                    <input type="number" name="labour" class="form-control w-50 @error('labour') is-invalid @enderror" value="{{ old('labour', $data->labour) }}" placeholder="Masukan ongkos kerja" {{ $data->on_invoice ? 'readonly' : '' }}>
                    @error('labour')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Transport</label>
                    <input type="number" name="transport" class="form-control w-50 @error('transport') is-invalid @enderror" value="{{ old('transport', $data->transport) }}" placeholder="Masukan transport" {{ $data->on_invoice ? 'readonly' : '' }}>
                    @error('transport')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <h5 class="text-black mb-0">Misc & Submit Button</h5>
        <small class="text-muted">Data tambahan dan tombol submit</small>
    </div>
    <div id="submit" class="col-md-9">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <div class="form-check">
                        <input name="quality_report" class="form-check-input" type="checkbox" value="1" id="checkboxQuality" {{ $data->quality_report ? 'checked' : ''}}>
                        <label class="form-check-label" for="checkboxQuality">
                            Quality Report
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input name="dealer_report" class="form-check-input" type="checkbox" value="1" id="checkboxDealer" {{ $data->dealer_report ? 'checked' : ''}}>
                        <label class="form-check-label" for="checkboxDealer">
                            Dealer Report
                        </label>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    @include('form.button.submit')
                </div>
            </div>
        </div>
    </div>
</div>

@include('form.header.end')