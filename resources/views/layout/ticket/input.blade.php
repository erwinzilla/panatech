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
            <small class="text-muted">Data ini yang nanti akan digunakan saat memberikan informasi tiket perbaikan</small>
            <br><small class="text-danger">*Wajib diisi</small>
            <br><small class="text-primary">*Langsung isi data berdasarkan database</small>
        </div>
        <div id="main" class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
                    <input type="hidden" name="name" value="{{ old('name', $data->name) }}">
                    <input type="hidden" name="branch_service" value="{{ old('branch_service', $data->branch_service) }}">
                    <div class="mb-3">
                        <label class="form-label">Fast Fill<span class="text-primary">*</span></label>
                        <input type="text" name="flash-fill" class="form-control w-50">
                        <small class="text-primary">Masukan nomor seri unit</small>
                        <br><small class="text-warning">Data harus dimasukan warranty terlebih dahulu</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Service Info<span class="text-danger">*</span></label>
                        <textarea name="service_info" class="form-control w-50 @error('service_info') is-invalid @enderror" placeholder="Masukan kendala unit">{{ old('service_info', $data->service_info) }}</textarea>
                        @error('service_info')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Job Status<span class="text-danger">*</span></label>
                        <select name="status" class="form-select w-50 @error('status') is-invalid @enderror">
                            @foreach($data_additional['status'] as $row)
                                <option value="{{ $row->id }}" {{ $row->id == old('status', $data->status) ? 'selected' :'' }}>{{ $row->name }}</option>
                            @endforeach
                        </select>
                        @error('status')
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
                        <input type="text" name="customer_name" class="form-control w-50 @error('customer_name') is-invalid @enderror" value="{{ old('customer_name', $data->customer_name) }}" placeholder="Masukan nama konsumen">
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
                                <input type="number" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $data->phone) }}" placeholder="Nomor telepon">
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
                        <textarea name="address" class="form-control w-50 @error('address') is-invalid @enderror" placeholder="Alamat Konsumen">{{ old('address', $data->address) }}</textarea>
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
                            <input type="date" name="purchase_date" class="form-control w-50 @error('purchase_date') is-invalid @enderror" value="{{ date('Y-m-d', strtotime(old('purchase_date', $data->purchase_date))) }}" placeholder="Masukan nomor garansi">
                            @error('purchase_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <h5 class="text-black mb-0">Submit</h5>
            <small class="text-muted">Submit button</small>
        </div>
        <div id="submit" class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-end">
                        @include('form.button.submit')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('form.header.end')
@endsection

@section('script')
    <script>
        const bsCollapse = new bootstrap.Collapse('#collapseIn', {
            toggle: false
        });

        let selectType = $('select[name="warranty_type"]');
        selectType.addEventListener('change', function () {
            if (selectType.value === '{{ IN_WARRANTY }}') {
                bsCollapse.show();
            } else {
                bsCollapse.hide();
            }
        });

        // isi otomatis berdasarkan data warranty konsumen
        let inputFlashFill = $('input[name="flash-fill"]');
        inputFlashFill.addEventListener('change', function () {
            let search = inputFlashFill.value;
            if (search) {
                // kirim data untuk menunjukan hasil pencarian
                let load_url = url('warranty/?type=form&search='+search);
                send_http(load_url, function (data) {
                    let obj = JSON.parse(data)

                    //show notification
                    alert.fire({
                        title: ucwords(obj.status),
                        text: obj.message,
                        icon: obj.status,
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    })

                    // lanjut jika sukses mengirim data
                    if (obj.status === 'success') {
                        //kita ubah jadi disable untuk flash fill nya
                        inputFlashFill.setAttribute('readonly','');

                        // isi data konsumen
                        $('input[name="customer_name"]').value = obj.data.customers.name;
                        $('input[name="phone"]').value = obj.data.customers.phone;
                        $('input[name="phone"]').setAttribute('readonly','');
                        $('input[name="phone2"]').value = obj.data.customers.phone2;
                        $('input[name="phone3"]').value = obj.data.customers.phone3;
                        $('textarea[name="address"]').value = obj.data.customers.address;
                        $('input[name="email"]').value = obj.data.customers.email;
                        $('#option-customer-type-'+obj.data.customers.type).checked = true;

                        // isi data barang
                        $('select[name="warranty_type"]').value = obj.data.type;
                        $('input[name="model"]').value = obj.data.model;
                        $('input[name="serial"]').value = obj.data.serial;
                        $('input[name="warranty_no"]').value = obj.data.warranty_no;
                        $('input[name="purchase_date"]').value = obj.data.purchase_date;

                        if (obj.data.type === {{ IN_WARRANTY }}) {
                            bsCollapse.show();
                        } else {
                            bsCollapse.hide();
                        }
                    }
                });
            }
        })

        // isi otomatis berdasarkan phone number konsumen

        let inputPhone = $('input[name="phone"]');
        // jika sudah dikunci tidak usah diproses
        if (!inputPhone.getAttribute('readonly')) {
            inputPhone.addEventListener('change', function () {
                let search = inputPhone.value;
                if (search) {
                    // kirim data untuk menunjukan hasil pencarian
                    let load_url = url('customer/?type=form&search='+search);
                    send_http(load_url, function (data) {
                        let obj = JSON.parse(data)

                        //show notification
                        alert.fire({
                            title: ucwords(obj.status),
                            text: obj.message,
                            icon: obj.status,
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        })

                        // lanjut jika sukses mengirim data
                        if (obj.status === 'success') {
                            //kita ubah jadi disable untuk flash fill nya
                            inputPhone.setAttribute('readonly','');

                            // isi data konsumen
                            $('input[name="customer_name"]').value = obj.data.name;
                            $('input[name="phone"]').value = obj.data.phone;
                            $('input[name="phone2"]').value = obj.data.phone2;
                            $('input[name="phone3"]').value = obj.data.phone3;
                            $('textarea[name="address"]').value = obj.data.address;
                            $('input[name="email"]').value = obj.data.email;
                            $('#option-customer-type-'+obj.data.type).checked = true;
                        }
                    });
                }
            })
        }
    </script>
@endsection
