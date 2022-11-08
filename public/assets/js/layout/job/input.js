const bsCollapse = new bootstrap.Collapse('#collapseIn', {
    toggle: false
});

let selectType = document.querySelector('select[name="warranty_type"]');
selectType.addEventListener('change', function () {
    if (selectType.value === 0) {
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

// select job type
let selectJobStatus = document.querySelector('select[name="status"]');
initJobStatus(selectJobStatus, true);
selectJobStatus.addEventListener('change', function () {
    initJobStatus(selectJobStatus);
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

                if (obj.data.warranty_type === 1) {
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

                    if (obj.data.type === 1) {
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

initInput('job');

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
            if (!init) {
                resetInput();
            }
            if (obj.data.actual_date > 0) {
                $('input[name="actual_start_at"]').setAttribute('readonly','');
                $('input[name="actual_start_at_time"]').setAttribute('readonly','');
                $('input[name="actual_end_at"]').setAttribute('readonly','');
                $('input[name="actual_end_at_time"]').setAttribute('readonly','');
                $('input[name="transport"]').setAttribute('readonly','');
                $('input[name="transport"]').value = 0;
            }
            if (obj.data.transport > 0) {
                $('input[name="transport"]').value = obj.data.transport;
            }
        }
    }, 'get', null, false)
}

function initJobStatus(el, init = false) {
    var search = el.options[el.selectedIndex].text;
    // kirim data untuk menunjukan hasil pencarian
    let load_url = url('status/?type=form&search='+search);
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
            if (!init) {
                resetInput();
            }
            // jika disable input PARTIAL and FULL
            if (obj.data.disable_input > 0) {
                $('input[name="flash-fill"]').setAttribute('readonly','');
                $('select[name="job_type"]').setAttribute('disabled','');
                $('textarea[name="accessories"]').setAttribute('readonly', '');
                $('textarea[name="condition"]').setAttribute('readonly', '');
                $('textarea[name="service_info"]').setAttribute('readonly', '');
                $('textarea[name="repair_info"]').setAttribute('readonly', '');
                $('input[name="created_at"]').setAttribute('readonly', '');
                $('input[name="created_at_time"]').setAttribute('readonly', '');
                $('input[name="repair_at"]').setAttribute('readonly', '');
                $('input[name="repair_at_time"]').setAttribute('readonly', '');
                $('input[name="collection_at"]').setAttribute('readonly', '');
                $('input[name="collection_at_time"]').setAttribute('readonly', '');
                $('input[name="actual_start_at"]').setAttribute('readonly', '');
                $('input[name="actual_start_at_time"]').setAttribute('readonly', '');
                $('input[name="actual_end_at"]').setAttribute('readonly', '');
                $('input[name="actual_end_at_time"]').setAttribute('readonly', '');

                if (obj.data.disable_input > 1) {
                    $('select[name="status"]').setAttribute('readonly','');
                    $('input[name="name"]').setAttribute('readonly', '');
                    $('textarea[name="note"]').setAttribute('readonly', '');
                    $('input[name="customer_name"]').setAttribute('readonly', '');
                    $('input[name="phone"]').setAttribute('readonly', '');
                    $('input[name="phone2"]').setAttribute('readonly', '');
                    $('input[name="phone3"]').setAttribute('readonly', '');
                    $('textarea[name="address"]').setAttribute('readonly', '');
                    $('input[name="email"]').setAttribute('readonly', '');
                    $('input[name="tax_id"]').setAttribute('readonly', '');
                    $('select[name="warranty_type"]').setAttribute('disabled','');
                    $('input[name="model"]').setAttribute('readonly', '');
                    $('input[name="serial"]').setAttribute('readonly', '');
                    $('input[name="warranty_no"]').setAttribute('readonly', '');
                    $('input[name="purchase_date"]').setAttribute('readonly', '');
                    $('input[name="labour"]').setAttribute('readonly', '');
                    $('input[name="transport"]').setAttribute('readonly', '');
                }
            }
        }
    }, 'get', null, false)
}

let el = $('#table-data-part');
if (el) {
    initTableBtnEdit(el.querySelector('table tbody'));
    initTableBtnDelete(el.querySelector('table tbody'));
}

function resetInput() {
    if ($('input[name="flash-fill"]').hasAttribute('readonly')) {
        $('input[name="flash-fill"]').removeAttribute('readonly','');
    }
    if ($('select[name="job_type"]').hasAttribute('disabled')) {
        $('select[name="job_type"]').removeAttribute('disabled','');
    }
    if ($('input[name="name"]').hasAttribute('readonly')) {
        $('input[name="name"]').removeAttribute('readonly','');
    }
    if ($('select[name="status"]').hasAttribute('disabled')) {
        $('select[name="status"]').removeAttribute('disabled','');
    }
    if ($('textarea[name="note"]').hasAttribute('readonly')) {
        $('textarea[name="note"]').removeAttribute('readonly','');
    }
    if ($('input[name="customer_name"]').hasAttribute('readonly')) {
        $('input[name="customer_name"]').removeAttribute('readonly','');
    }
    if ($('input[name="phone"]').hasAttribute('readonly')) {
        $('input[name="phone"]').removeAttribute('readonly','');
    }
    if ($('input[name="phone2"]').hasAttribute('readonly')) {
        $('input[name="phone2"]').removeAttribute('readonly','');
    }
    if ($('input[name="phone3"]').hasAttribute('readonly')) {
        $('input[name="phone3"]').removeAttribute('readonly','');
    }
    if ($('textarea[name="address"]').hasAttribute('readonly')) {
        $('textarea[name="address"]').removeAttribute('readonly','');
    }
    if ($('input[name="email"]').hasAttribute('readonly')) {
        $('input[name="email"]').removeAttribute('readonly','');
    }
    if ($('input[name="tax_id"]').hasAttribute('readonly')) {
        $('input[name="tax_id"]').removeAttribute('readonly','');
    }
    if ($('select[name="warranty_type"]').hasAttribute('disabled')) {
        $('select[name="warranty_type"]').removeAttribute('disabled','');
    }
    if ($('input[name="model"]').hasAttribute('readonly')) {
        $('input[name="model"]').removeAttribute('readonly','');
    }
    if ($('input[name="serial"]').hasAttribute('readonly')) {
        $('input[name="serial"]').removeAttribute('readonly','');
    }
    if ($('input[name="warranty_no"]').hasAttribute('readonly')) {
        $('input[name="warranty_no"]').removeAttribute('readonly','');
    }
    if ($('input[name="purchase_date"]').hasAttribute('readonly')) {
        $('input[name="purchase_date"]').removeAttribute('readonly','');
    }
    if ($('textarea[name="accessories"]').hasAttribute('readonly')) {
        $('textarea[name="accessories"]').removeAttribute('readonly','');
    }
    if ($('textarea[name="condition"]').hasAttribute('readonly')) {
        $('textarea[name="condition"]').removeAttribute('readonly','');
    }
    if ($('textarea[name="service_info"]').hasAttribute('readonly')) {
        $('textarea[name="service_info"]').removeAttribute('readonly','');
    }
    if ($('textarea[name="repair_info"]').hasAttribute('readonly')) {
        $('textarea[name="repair_info"]').removeAttribute('readonly','');
    }
    if ($('input[name="created_at"]').hasAttribute('readonly')) {
        $('input[name="created_at"]').removeAttribute('readonly','');
    }
    if ($('input[name="created_at_time"]').hasAttribute('readonly')) {
        $('input[name="created_at_time"]').removeAttribute('readonly','');
    }
    if ($('input[name="repair_at"]').hasAttribute('readonly')) {
        $('input[name="repair_at"]').removeAttribute('readonly','');
    }
    if ($('input[name="repair_at_time"]').hasAttribute('readonly')) {
        $('input[name="repair_at_time"]').removeAttribute('readonly','');
    }
    if ($('input[name="collection_at"]').hasAttribute('readonly')) {
        $('input[name="collection_at"]').removeAttribute('readonly','');
    }
    if ($('input[name="collection_at_time"]').hasAttribute('readonly')) {
        $('input[name="collection_at_time"]').removeAttribute('readonly','');
    }
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
    if ($('input[name="labour"]').hasAttribute('readonly')) {
        $('input[name="labour"]').removeAttribute('readonly','');
    }
    if ($('input[name="transport"]').hasAttribute('readonly')) {
        $('input[name="transport"]').removeAttribute('readonly','');
    }
}