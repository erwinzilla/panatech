@extends('layout.template')

@section('title', ucwords($title))

@section('sidebar')
    @include($config['blade'].'.sidebar')
@endsection

@section('content')
    @include('component.breadcrumb')

    <ul class="nav nav-pills" id="tabJob" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="main-tab" data-bs-toggle="tab" data-bs-target="#main-tab-pane" type="button" role="tab" aria-controls="main-tab-pane" aria-selected="true">Main Menu</button>
        </li>
        @if($type == 'edit')
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="part-tab" data-bs-toggle="tab" data-bs-target="#part-tab-pane" type="button" role="tab" aria-controls="part-tab-pane" aria-selected="false">Spare Part</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="invoice-tab" data-bs-toggle="tab" data-bs-target="#invoice-tab-pane" type="button" role="tab" aria-controls="invoice-tab-pane" aria-selected="false">Invoice</button>
            </li>
        @endif
    </ul>
    <div class="tab-content" id="tabJobContent">
        <div class="tab-pane fade show active" id="main-tab-pane" role="tabpanel" aria-labelledby="main-tab" tabindex="0">
            @include($config['blade'].'.input.main')
        </div>
        @if($type == 'edit')
            <div class="tab-pane fade" id="part-tab-pane" role="tabpanel" aria-labelledby="part-tab" tabindex="0">
                @include($config['blade'].'.input.part')
            </div>
            <div class="tab-pane fade" id="invoice-tab-pane" role="tabpanel" aria-labelledby="invoice-tab" tabindex="0">
                // invoice
            </div>
        @endif
    </div>

    @include('component.modal.table')
@endsection

@section('script')
    <script>
        const bsCollapse = new bootstrap.Collapse('#collapseIn', {
            toggle: false
        });

        let selectType = document.querySelector('select[name="warranty_type"]');
        selectType.addEventListener('change', function () {
            if (selectType.value === '{{ IN_WARRANTY }}') {
                bsCollapse.show();
            } else {
                bsCollapse.hide();
            }
        });

        // select job type
        let selectJobType = document.querySelector('select[name="job_type"]');
        initJobType(selectJobType, true);
        selectJobType.addEventListener('change', function () {
            initJobType(selectJobType);
        });

        // isi otomatis berdasarkan data warranty konsumen
        let inputFlashFill = $('input[name="flash-fill"]');
        inputFlashFill.addEventListener('change', function () {
            let search = inputFlashFill.value;
            if (search) {
                // kirim data untuk menunjukan hasil pencarian
                let load_url = url('ticket/?type=form&search='+search);
                send_http(load_url, function (data) {
                    let obj = JSON.parse(data)

                    //show notification
                    Toast.fire({
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

                        // isi data ticket
                        $('select[name="status"]').value = obj.data.status;
                        $('textarea[name="service_info"]').value = obj.data.service_info;
                        $('input[name="ticket"]').value = obj.data.id;

                        // isi data konsumen
                        $('input[name="customer_name"]').value = obj.data.customer_name;
                        $('input[name="phone"]').value = obj.data.phone;
                        $('input[name="phone"]').setAttribute('readonly','');
                        $('input[name="phone2"]').value = obj.data.phone2;
                        $('input[name="phone3"]').value = obj.data.phone3;
                        $('textarea[name="address"]').value = obj.data.address;
                        $('input[name="email"]').value = obj.data.email;
                        $('#option-customer-type-'+obj.data.customer_type).checked = true;

                        // isi data barang
                        $('select[name="warranty_type"]').value = obj.data.warranty_type;
                        $('input[name="model"]').value = obj.data.model;
                        $('input[name="serial"]').value = obj.data.serial;
                        if (obj.data.serial) {
                            $('input[name="serial"]').setAttribute('readonly','');
                        }
                        $('input[name="warranty_no"]').value = obj.data.warranty_no;
                        $('input[name="purchase_date"]').value = new Date(obj.data.purchase_date).toLocaleDateString('id-ID', {day: '2-digit', month: '2-digit', year: 'numeric'});

                        if (obj.data.warranty_type === {{ IN_WARRANTY }}) {
                            bsCollapse.show();
                        } else {
                            bsCollapse.hide();
                        }
                    }
                }, 'get', null, false);
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
                        Toast.fire({
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
                            $('input[name="tax_id"]').value = obj.data.tax_id;
                        }
                    }, 'get', null, false);
                }
            })
        }

        // isi otomatis berdasarkan nomor serial
        let inputSerial = $('input[name="serial"]');
        // jika sudah dikunci tidak usah diproses
        if (!inputSerial.getAttribute('readonly')) {
            inputSerial.addEventListener('change', function () {
                let search = inputSerial.value;
                if (search) {
                    // kirim data untuk menunjukan hasil pencarian
                    let load_url = url('warranty/?type=form&search='+search);
                    send_http(load_url, function (data) {
                        let obj = JSON.parse(data)

                        //show notification
                        Toast.fire({
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
                            inputSerial.setAttribute('readonly','');

                            // isi data konsumen
                            $('input[name="customer_name"]').value = obj.data.customers.name;
                            $('input[name="phone"]').value = obj.data.customers.phone;
                            $('input[name="phone"]').setAttribute('readonly','');
                            $('input[name="phone2"]').value = obj.data.customers.phone2;
                            $('input[name="phone3"]').value = obj.data.customers.phone3;
                            $('textarea[name="address"]').value = obj.data.customers.address;
                            $('input[name="email"]').value = obj.data.customers.email;
                            $('#option-customer-type-'+obj.data.customers.type).checked = true;
                            $('input[name="tax_id"]').value = obj.data.customers.tax_id;

                            // isi data barang
                            $('select[name="warranty_type"]').value = obj.data.type;
                            $('input[name="model"]').value = obj.data.model;
                            $('input[name="serial"]').value = obj.data.serial;
                            $('input[name="warranty_no"]').value = obj.data.warranty_no;
                            $('input[name="purchase_date"]').value = new Date(obj.data.purchase_date).toLocaleDateString('id-ID', {day: '2-digit', month: '2-digit', year: 'numeric'});

                            if (obj.data.type === {{ IN_WARRANTY }}) {
                                bsCollapse.show();
                            } else {
                                bsCollapse.hide();
                            }
                        }
                    }, 'get', null, false);
                }
            })
        }

        // isi otomatis berdasarkan phone number konsumen
        let btnFindTax = $('#find-tax');
        // jika sudah dikunci tidak usah diproses
        if (btnFindTax) {
            btnFindTax.addEventListener('click', function (e) {
                e.preventDefault();

                // kirim data untuk menunjukan hasil pencarian
                let search = inputPhone.value;
                let load_url = url('customer/?type=form&search='+search);
                send_http(load_url, function (data) {
                    let obj = JSON.parse(data)

                    // lanjut jika sukses mengirim data
                    if (obj.status === 'success') {
                        if (obj.data.tax_id) {
                            //show notification
                            Toast.fire({
                                title: 'Success',
                                text: 'Sukses memasukan NPWP atau NIK',
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            })

                            $('input[name="tax_id"]').value = obj.data.tax_id;
                        } else {
                            //show notification
                            alert.fire({
                                title: 'Error',
                                text: 'Konsumen tidak memiliki nik',
                                icon: 'error',
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            })
                        }
                    }
                }, 'get', null, false)
            });
        }

        initInput('{{ $config['url'] }}');

        function initJobType(el, init = false) {
            var search = el.options[el.selectedIndex].text;
            // kirim data untuk menunjukan hasil pencarian
            let load_url = url('job/type/?type=form&search='+search);
            send_http(load_url, function (data) {
                let obj = JSON.parse(data)

                if (!init) {
                    //show notification
                    Toast.fire({
                        title: ucwords(obj.status),
                        text: obj.message,
                        icon: obj.status,
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    })
                }

                // lanjut jika sukses mengirim data
                if (obj.status === 'success') {
                    if ($('input[name="actual_start_at"]').hasAttribute('readonly')) {
                        $('input[name="actual_start_at"]').removeAttribute('readonly','');
                    }
                    if ($('input[name="actual_start_at_time"]').hasAttribute('readonly')) {
                        $('input[name="actual_start_at_time"]').removeAttribute('readonly','');
                    }
                    if ($('input[name="actual_end_at"]').hasAttribute('readonly')) {
                        $('input[name="actual_end_at"]').removeAttribute('readonly','');
                    }
                    if ($('input[name="actual_end_at_time"]').hasAttribute('readonly')) {
                        $('input[name="actual_end_at_time"]').removeAttribute('readonly','');
                    }
                    if ($('input[name="transport"]').hasAttribute('readonly')) {
                        $('input[name="transport"]').removeAttribute('readonly','');
                    }

                    if (obj.data.actual_date) {
                        $('input[name="actual_start_at"]').setAttribute('readonly','');
                        $('input[name="actual_start_at_time"]').setAttribute('readonly','');
                        $('input[name="actual_end_at"]').setAttribute('readonly','');
                        $('input[name="actual_end_at_time"]').setAttribute('readonly','');
                        $('input[name="transport"]').setAttribute('readonly','');
                        $('input[name="transport"]').value = 0;
                    }
                    if (obj.data.transport) {
                        $('input[name="transport"]').value = obj.data.transport;
                    }
                }
            }, 'get', null, false)
        }

        let el = $('#table-data-part').querySelector('table tbody');
        if (el) {
            initTableBtnEdit(el);
            initTableBtnDelete(el);
        }
    </script>
@endsection
