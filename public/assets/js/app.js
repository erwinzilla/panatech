const base_url = document.currentScript.getAttribute('url')+'/';
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

// setting alert
let alert = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-primary btn-lg text-white me-2',
        cancelButton: 'btn btn-outline-primary btn-lg',
        popup: 'rounded-4',
        title: 'text-dark'
    },
    buttonsStyling: false
});

let Toast = Swal.mixin({
    toast: true,
    position: 'bottom-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    customClass: {
        popup: 'card',
        title: 'text-default'
    },
    buttonsStyling: false,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

// saat semua telah ter-load
document.addEventListener("DOMContentLoaded", function() {
    let prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)");
    let currentTheme = localStorage.getItem("theme");

    // jika tidak ada data di storage maka
    if (!!currentTheme) {
        currentTheme = prefersDarkScheme.matches ? 'dark' : 'light';
        const load_url = url('theme/'+currentTheme);
        send_http(load_url, function (data) {
            if (data === 'success') {
                toggle_theme(currentTheme)
            }
        }, 'get', null, false);
    }

    // Toggle Dark Mode
    let btn_mode = $('#btn-mode');
    if (btn_mode) {
        btn_mode.addEventListener('click', function () {
            let currentTheme = localStorage.getItem("theme");
            let cur_theme = 'dark';

            if (currentTheme == "dark") {
                cur_theme = 'light';
            } else if (currentTheme == "light") {
                cur_theme = 'dark';
            }

            let load_url = url('theme/'+cur_theme);
            send_http(load_url, function (data) {
                if (data === 'success') {
                    toggle_theme(cur_theme, true);
                }
            }, 'get', null, false);

            // Finally, let's save the current preference to localStorage to keep using it
            if (localStorage.setItem("theme", cur_theme)) {
                console.log(cur_theme)
            }
        });
    }

    // Toggle Dark Mode for Login
    let btn_mode_login = document.querySelector('#btn-mode-login');
    if (btn_mode_login) {
        btn_mode_login.addEventListener('click', function () {
            let currentTheme = localStorage.getItem("theme");
            let cur_theme = 'dark';

            if (currentTheme === "dark") {
                cur_theme = 'light';
            } else if (currentTheme === "light") {
                cur_theme = 'dark';
            }

            toggle_theme(cur_theme);

            // Finally, let's save the current preference to localStorage to keep using it
            if (localStorage.setItem("theme", cur_theme)) {
                console.log(cur_theme)
            }
        });
    }

    // confirmation restore data
    let res_act = $s('.restore-action-link');
    if (res_act) {
        res_act.forEach(function (el) {
            el.addEventListener('click', function (e) {
                e.preventDefault();

                // show confirmation
                alert.fire({
                    title: 'Restore Data',
                    text: "Are you sure want to restore data?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sure',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = el.href;
                    }
                });
            });
        });
    }

    // confirmation delete perm all
    let del_act = $s('.delete-action-link');
    if (del_act) {
        del_act.forEach(function (el) {
            el.addEventListener('click', function (e) {
                e.preventDefault();

                // show confirmation
                alert.fire({
                    title: 'Delete Permanent All Data',
                    text: "Are you sure want to delete permanent all data?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sure',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = el.href;
                    }
                });
            });
        });
    }

    // table function
    let table_data = $('#table-data');
    if (table_data) {
        initTable(table_data);

        // tambahkan funngsi saat kita mengklik link fungsi tabel
        table_data.addEventListener('click', function (e){
            // jika yang di klik adalah sort column dari tabel
            if (e.target.classList.contains('dropdown-item') || e.target.classList.contains('icon-table') || e.target.classList.contains('page-link')) {
                e.preventDefault();

                let el = e.target;
                if (e.target.classList.contains('icon-table')) {
                    el = e.target.closest('a')
                }

                // kirim data untuk menunjukan hasil pencarian
                let load_url = el.href;
                send_http(load_url, function (data) {
                    table_data.innerHTML = data;
                });

                init();
            }
        })
    }

    // modal user table show
    let btn_table_modal = $s('.btn-table-modal');
    if (btn_table_modal) {
        btn_table_modal.forEach(function (el) {
            el.addEventListener('click', function () {
                let table_data_modal = $('#table-data-modal');
                if (table_data_modal) {
                    let target = el.dataset.target;

                    // setting header
                    table_data_modal.closest('.modal').querySelector('.modal-title').innerText = ucwords(target);

                    // kirim data untuk menunjukan hasil pencarian
                    let load_url = url(target+'/?type=choose&target=table');
                    send_http(load_url, function (data) {
                        table_data_modal.innerHTML = data;
                        table_data_modal.dataset.url = url(target+'/?type=choose&target=table');
                    });
                }

                init();
            });
        });
    }

    // modal user table show
    let btn_input_modal = $s('.btn-input-modal');
    if (btn_input_modal) {
        btn_input_modal.forEach(function (el) {
            el.addEventListener('click', function () {
                let table_data_modal = $('#table-data-modal');
                if (table_data_modal) {
                    let target = el.dataset.target;

                    // setting header
                    table_data_modal.closest('.modal').querySelector('.modal-title').innerText = 'Input Data';
                    table_data_modal.closest('.modal').querySelector('.modal-dialog').className = 'modal-dialog modal-sm';
                    // kirim data untuk menunjukan hasil pencarian
                    let load_url = url(target);
                    send_http(load_url, function (data) {
                        table_data_modal.innerHTML = data;
                    });
                }

                init();
            });
        });
    }

    // table function
    let table_data_modal = $('#table-data-modal');
    if (table_data_modal) {
        // tambahkan fungsi jika ada perubahan pada search dan perpage selector
        table_data_modal.addEventListener('change', function (){
            if ($('select[name="perPage"]')) {
                let perPage = $('select[name="perPage"]').value;
                let search = $('input[name="search"]').value;

                let params = '&perPage=' + perPage +
                    '&search=' + search;

                // kirim data untuk menunjukan hasil pencarian
                let load_url = table_data_modal.dataset.url + params;
                send_http(load_url, function (data) {
                    table_data_modal.innerHTML = data;
                });

                init();
            }
        });

        // tambahkan funngsi saat kita mengklik link fungsi tabel
        table_data_modal.addEventListener('click', function (e){
            // jika yang di klik adalah sort column dari tabel
            if (e.target.classList.contains('dropdown-item') || e.target.classList.contains('icon-table') || e.target.classList.contains('page-link')) {
                e.preventDefault();

                let el = e.target;
                if (e.target.classList.contains('icon-table')) {
                    el = e.target.closest('a')
                }

                // kirim data untuk menunjukan hasil pencarian
                let load_url = el.href;
                send_http(load_url, function (data) {
                    table_data_modal.innerHTML = data;
                });

                init();
            }
        })
    }

    // initialisasi date picker
    const datePickerInput = $s('input[date-picker]');
    datePickerInput.forEach((el) => {
        let datepicker = new Datepicker(el, {
            autohide: true,
            format: 'dd/mm/yyyy',
            todayBtn: true,
            todayBtnMode: 1,
            todayHighlight: true,
            defaultViewDate: 'today',
        });

        datepicker.element.addEventListener('click', () => {
            let inputDate = $('input[name="job_update_at"]')
            if (inputDate) {
                let id = inputDate.dataset.id;
                // kirim data untuk menunjukan hasil pencarian
                let load_url = url('config/'+id);
                send_http(load_url, function (data) {
                    let obj = JSON.parse(data);
                    if (obj.status === 'success') {
                        if (inputDate.classList.contains('is-invalid')) {
                            inputDate.classList.remove('is-invalid')
                        }
                        inputDate.classList.add('is-valid');
                    } else {
                        if (inputDate.classList.contains('is-valid')) {
                            inputDate.classList.remove('is-valid')
                        }
                        inputDate.classList.add('is-invalid');
                    }
                },'post', '_method=put&validate=true&job_update_at='+inputDate.value, false)
            }
        })
    })
});

// inisialisasi kembali untuk elemen dinamis
function init() {
    // boostrap tooltip
    new bootstrap.Tooltip(document.body, {
        selector: '[data-bs-toggle="tooltip"]'
    })
}

function $(query) {
    if (document.querySelector(query)) {
        return document.querySelector(query)
    }
}

function $s(query) {
    if (document.querySelectorAll(query)) {
        return document.querySelectorAll(query)
    }
}

function confirm_delete(id) {
    let el = $('#delete-'+id);

    // show confirmation
    alert.fire({
        title: 'Delete Data',
        text: "Are you sure want to delete data?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sure',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            el.submit();
        }
    });
}

function confirm_delete_link(id) {
    let el = document.getElementById('delete-link-'+id);

    // show confirmation
    alert.fire({
        title: 'Delete Permanent Data',
        text: "Are you sure want to delete permanent data?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sure',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = el.href;
        }
    });
}

function choose(name, value, target = null) {
    if (name && value) {
        if (!target) {
            let input = $('input[name="'+name+'"]');
            if (input) {
                // isi hidden value
                input.value = value

                // callback hasil data
                let box = $('#'+name+'-data');
                if (box) {
                    // kirim data untuk menunjukan hasil pencarian
                    let load_url = url(name+'/'+value);
                    send_http(load_url, function (data) {
                        box.innerHTML = data;
                    })
                }
            }
        }

        if (target === 'table') {
            let box = $('#table-data-'+name);
            if (box) {
                // kirim data untuk menunjukan hasil pencarian
                let load_url = url(name+'/'+value);
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
                        let tbody = box.querySelector('table tbody');

                        // hapus no entries
                        if (tbody.querySelector('tr')) {
                            if (tbody.querySelector('tr').classList.contains('no-entries')) {
                                tbody.querySelector('tr').outerHTML = '';
                            }
                        }

                        // jika data sudah ada di table
                        const rows = tbody.querySelectorAll('tr');
                        if (rows.length > 0) {
                            let has_sku = false;
                            rows.forEach((row) => {
                                if (row.querySelector('#sku-'+obj.data.sku)) {
                                    has_sku = true
                                }
                            })

                            if (has_sku) {
                                const row = tbody.querySelector('tr #sku-'+obj.data.sku).closest('tr');
                                // update
                                const price = parseInt(row.querySelector(`#price-${obj.data.sku}`).innerText);
                                const qty = parseInt(row.querySelector(`#qty-${obj.data.sku}`).innerText) + 1;
                                row.querySelector(`#qty-${obj.data.sku}`).innerText = qty;
                                row.querySelector(`#total-${obj.data.sku}`).innerText = price * qty;
                            } else {
                                // create baru
                                // get number
                                const rows = box.querySelectorAll('table tbody tr');

                                tbody.innerHTML += `
                                    <tr>
                                        <td class="text-center num">${rows.length + 1}</td>
                                        <td id="sku-${obj.data.sku}">${obj.data.sku}</td>
                                        <td>${obj.data.name}</td>
                                        <td id="price-${obj.data.sku}">${obj.data.price}</td>
                                        <td id="qty-${obj.data.sku}">1</td>
                                        <td id="total-${obj.data.sku}">${obj.data.price * 1}</td>
                                        <td class="pe-3 w-2-slot">
                                            <div class="d-flex">
                                                <button type="button" class="btn btn-warning btn-icon me-2 edit" data-bs-toggle="tooltip" data-bs-title="Edit" data-id="${obj.data.sku}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon-sm">
                                                        <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                                                        <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
                                                    </svg>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-icon delete" data-bs-toggle="tooltip" data-bs-title="Delete" data-id="${obj.data.sku}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon-sm">
                                                        <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>`;
                            }
                        } else {
                            tbody.innerHTML += `
                                <tr>
                                    <td class="text-center num">${rows.length + 1}</td>
                                    <td id="sku-${obj.data.sku}" data-name="sku">${obj.data.sku}</td>
                                    <td data-name="name">${obj.data.name}</td>
                                    <td id="price-${obj.data.sku}" data-name="price">${obj.data.price}</td>
                                    <td id="qty-${obj.data.sku}" data-name="qty">1</td>
                                    <td id="total-${obj.data.sku}">${obj.data.price * 1}</td>
                                    <td class="pe-3 w-2-slot">
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-warning btn-icon me-2 edit" data-bs-toggle="tooltip" data-bs-title="Edit" data-id="${obj.data.sku}">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon-sm">
                                                    <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                                                    <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
                                                </svg>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-icon delete" data-bs-toggle="tooltip" data-bs-title="Delete" data-id="${obj.data.sku}">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon-sm">
                                                    <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>`;
                        }
                        initTableBtnEdit(tbody);
                        initTableBtnDelete(tbody);

                        // update status
                        updateTableStatus('Data berubah, silahkan submit untuk menyimpan data', 'error')
                    }
                })
            }
        }
    }
}

function send_http(url_http, callback, method = 'get', param = null, loading = true, json = false) {
    // console.log(url);
    let http = (window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP'));

    http.onreadystatechange = function () {
        if (http.readyState === 4 && http.status === 200) {
            if (loading) {
                alert.close();
            }
            if (typeof callback === 'function') callback(http.responseText);
        }
    };

    if (loading) {
        alert.fire({
            title: 'Loading...',
            imageUrl: url('/assets/images/loading.svg'),
            imageWidth: 150,
            imageHeight: 150,
            imageAlt: 'loading',
            timer: 120000,
            showConfirmButton: false,
            // didOpen: () => {
            //     alert.showLoading()
            // }
        }).then((result) => {
            if (result.dismiss === alert.DismissReason.timer) {
                alert.fire(
                    'Request Time Out',
                    'Cannot get data from database, please refresh page or try again',
                    'error'
                )
            }
        });
    }

    if (method === 'get') {
        http.open(method, url_http);
        http.send()
    }

    if (method === 'post' || method === 'put') {
        http.open(method, url_http, true);
        if (json) {
            http.setRequestHeader("Content-Type", "application/json");
        } else {
            http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        }
        http.setRequestHeader("X-CSRF-TOKEN", document.head.querySelector("[name=csrf-token]").content);
        http.send(param)
    }
}

function url(query) {
    return base_url+query;
}

function ucwords (str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}

function toggle_theme(mode, icon = false) {
    if (mode === 'dark'){
        // ganti tema
        if (document.body.classList.contains('light-theme')){
            document.body.classList.remove('light-theme');
        }
        document.body.classList.add('dark-theme');
    }

    if (mode === 'light'){
        // Ganti tema
        if (document.body.classList.contains('dark-theme')){
            document.body.classList.remove('dark-theme');
        }
        document.body.classList.add('light-theme');
    }

    if (icon) {
        // ganti icon
        const load_url = url('theme/'+mode+'/icon');
        send_http(load_url, function (data) {
            let btn_mode = $('#btn-mode');
            btn_mode.innerHTML = data;
        });
    }
}

function initInput(target) {
    let inputs = $s('input[validate]');
    inputs.forEach(function (el) {
        el.addEventListener('change', function (e) {
            e.preventDefault();
            if (el.value) {
                const id = $('input[name="id"]').value;
                // kirim data untuk menunjukan hasil pencarian
                let load_url = url(target+'/validate/'+id);
                send_http(load_url, function (data) {
                    let obj = JSON.parse(data)
                    if (obj[el.getAttribute('name')]) {
                        if (el.classList.contains('is-valid')) {
                            el.classList.remove('is-valid')
                        }
                        el.classList.add('is-invalid');
                        if (!el.closest('div').querySelector('.invalid-feedback')) {
                            el.outerHTML += '<div class="invalid-feedback">'+obj[el.getAttribute('name')][0]+'</div>';
                        } else {
                            el.closest('div').querySelector('.invalid-feedback').innerHTML = obj[el.getAttribute('name')][0];
                        }
                        initInput(target);
                    } else {
                        if (el.classList.contains('is-invalid')) {
                            el.classList.remove('is-invalid')
                        }
                        el.classList.add('is-valid');
                    }
                }, 'post', el.getAttribute('name')+'='+el.value+'&validate=true', false)
            }
        })
    });

    let textareas = $s('textarea[validate]');
    textareas.forEach(function (el) {
        el.addEventListener('change', function (e) {
            e.preventDefault();
            if (el.value) {
                const id = $('input[name="id"]').value;
                // kirim data untuk menunjukan hasil pencarian
                let load_url = url(target+'/validate/'+id);
                send_http(load_url, function (data) {
                    console.log(data);
                    let obj = JSON.parse(data)
                    if (obj[el.getAttribute('name')]) {
                        if (el.classList.contains('is-valid')) {
                            el.classList.remove('is-valid')
                        }
                        el.classList.add('is-invalid');
                        if (!el.closest('div').querySelector('.invalid-feedback')) {
                            el.outerHTML += '<div class="invalid-feedback">'+obj[el.getAttribute('name')][0]+'</div>';
                        } else {
                            el.closest('div').querySelector('.invalid-feedback').innerHTML = obj[el.getAttribute('name')][0];
                        }
                        initInput(target);
                    } else {
                        if (el.classList.contains('is-invalid')) {
                            el.classList.remove('is-invalid')
                        }
                        el.classList.add('is-valid');
                    }
                }, 'post', el.getAttribute('name')+'='+el.value+'&validate=true', false)
            }
        })
    });
}

function initTable(el) {
    // tambahkan fungsi jika ada perubahan pada search dan perpage selector
    let perPage = $('select[name="perPage"]');
    let search = $('input[name="search"]');

    perPage.addEventListener('change', () => {
        let params = 'perPage=' + perPage.value +
            '&search=' + search.value +
            '&target=table';

        // kirim data untuk menunjukan hasil pencarian
        let load_url = window.location.href + '?' + params;
        send_http(load_url, function (data) {
            el.innerHTML = data;
            initTable(el);
        });
    })

    search.addEventListener('change', () => {
        let params = 'perPage=' + perPage.value +
            '&search=' + search.value +
            '&target=table';

        // kirim data untuk menunjukan hasil pencarian
        let load_url = window.location.href + '?' + params;
        send_http(load_url, function (data) {
            el.innerHTML = data;
            initTable(el);
        });
    })

    let inputDate = $('input[name="job_update_at"]')
    if (inputDate) {
        inputDate.addEventListener('change', () => {
            let id = inputDate.dataset.id;
            // kirim data untuk menunjukan hasil pencarian
            let load_url = url('config/'+id);
            send_http(load_url, function (data) {
                let obj = JSON.parse(data);
                if (obj.status === 'success') {
                    if (inputDate.classList.contains('is-invalid')) {
                        inputDate.classList.remove('is-invalid')
                    }
                    inputDate.classList.add('is-valid');
                } else {
                    if (inputDate.classList.contains('is-valid')) {
                        inputDate.classList.remove('is-valid')
                    }
                    inputDate.classList.add('is-invalid');
                }
            },'post', '_method=put&validate=true&job_update_at='+inputDate.value, false)
        })
    }

    init()
}

function initTableBtnEdit(el) {
    // jika data sudah ada di table
    const btn_edits = el.querySelectorAll('tr td .edit');
    if (btn_edits) {
        btn_edits.forEach((el_btn) => {
            el_btn.addEventListener('click', (e) => {
                e.preventDefault();

                // jika yang ter-klik svg nya
                const btn_edit = e.target.closest('td').querySelector('.edit');
                const id = btn_edit.dataset.id;

                const row = btn_edit.closest('tr');
                // price element
                const price_el = row.querySelector(`#price-${id}`);
                if (price_el) {
                    const price = price_el.innerText;
                    price_el.innerHTML = `<input type="number" name="price" class="form-control" value="${price}">`
                }

                // qty element
                const qty_el = row.querySelector(`#qty-${id}`);
                if (qty_el) {
                    const qty = qty_el.innerText;
                    qty_el.innerHTML = `<input type="number" name="qty" class="form-control" value="${qty}">`
                }

                // change btn edit element
                el_btn.outerHTML = `<button type="button" class="btn btn-success btn-icon me-2 apply" data-bs-toggle="tooltip" data-bs-title="Apply" data-id="${id}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon-sm">
                                                          <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>`

                initTableBtnApply(el)

                // hapus tooltip nyangkut
                let tooltip_ui = document.querySelector('.tooltip');
                if (tooltip_ui) {
                    tooltip_ui.outerHTML = '';
                }
            })
        })
    }
}

function initTableBtnApply(el){
    // jika data sudah ada di table
    const btn_applies = el.querySelectorAll('tr td .apply');
    if (btn_applies) {
        btn_applies.forEach((el_btn) => {
            el_btn.addEventListener('click', (e) => {
                e.preventDefault();

                // jika yang ter-klik svg nya
                const btn_apply = e.target.closest('td').querySelector('.apply');
                const id = btn_apply.dataset.id;

                const row = btn_apply.closest('tr');

                let price = 0, qty = 0;

                // price element
                const price_el = row.querySelector(`#price-${id}`);
                if (price_el) {
                    price = price_el.querySelector('input[name="price"]').value;
                    price_el.innerText = price;
                }

                // qty element
                const qty_el = row.querySelector(`#qty-${id}`);
                if (qty_el) {
                    qty = qty_el.querySelector('input[name="qty"]').value;
                    qty_el.innerText = qty;
                }

                // total element
                const total_el = row.querySelector(`#total-${id}`);
                if (total_el) {
                    const total = price * qty;
                    total_el.innerHTML = total;
                }

                // change btn edit element
                el_btn.outerHTML = `<button type="button" class="btn btn-warning btn-icon me-2 edit" data-bs-toggle="tooltip" data-bs-title="Edit" data-id="${id}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon-sm">
                                                            <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                                                            <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
                                                        </svg>
                                                    </button>`

                initTableBtnEdit(el)

                // update status
                let status = $('#table-status')
                updateTableStatus('Data berubah, silahkan submit untuk menyimpan data', 'error')

                // hapus tooltip nyangkut
                let tooltip_ui = document.querySelector('.tooltip');
                if (tooltip_ui) {
                    tooltip_ui.outerHTML = '';
                }
            })
        })
    }
}

function initTableBtnDelete(el) {
    // jika data sudah ada di table
    const btn_deletes = el.querySelectorAll('tr td .delete');
    if (btn_deletes) {
        btn_deletes.forEach((el_btn) => {
            el_btn.addEventListener('click', (e) => {
                e.preventDefault();

                // jika yang ter-klik svg nya
                const btn_delete = e.target.closest('td').querySelector('.delete');

                const id = $('input[name="job"]').value;
                const sku = btn_delete.dataset.id;
                const load_url = url('job/part')
                send_http(load_url, (data) => {
                    let obj = JSON.parse(data)

                    //show notification
                    Toast.fire({
                        title: ucwords(obj.status),
                        text: obj.message,
                        icon: obj.status,
                        timer: 5000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    })

                    // lanjut jika sukses mengirim data
                    if (obj.status === 'success') {
                        const row = btn_delete.closest('tr');
                        row.outerHTML = '';

                        // get number
                        // urut kembali nomor table
                        let count = 1; //defaut index
                        const rows = el.closest('.table').querySelectorAll('tbody tr .num');
                        if (rows) {
                            rows.forEach((el_num) => {
                                el_num.innerText = count;
                                count += 1;
                            })
                        }
                    }

                    // lanjut jika sukses mengirim data
                    if (obj.status === 'info') {
                        const row = btn_delete.closest('tr');
                        row.outerHTML = '';

                        // get number
                        // urut kembali nomor table
                        let count = 1; //defaut index
                        const rows = el.closest('.table').querySelectorAll('tbody tr .num');
                        if (rows) {
                            rows.forEach((el_num) => {
                                el_num.innerText = count;
                                count += 1;
                            })
                        }
                    }
                }, 'post', '_method=delete&job='+id+'&sku='+sku)

                const rows = el.querySelectorAll('tbody tr');
                if (rows.length === 0) {
                    el.innerHtml += '<tr class="no-entries">' +
                        '<td colspan="7" class="text-muted text-center">Silahkan Masukan Part</td>' +
                        '</tr>'
                }

                // update status
                let status = $('#table-status')
                updateTableStatus('Data berubah, silahkan submit untuk menyimpan data', 'error')

                // hapus tooltip nyangkut
                let tooltip_ui = document.querySelector('.tooltip');
                if (tooltip_ui) {
                    tooltip_ui.outerHTML = '';
                }
            })
        })
    }
}

function sendPart(id) {
    let table = $('#table-data-part');
    if (table && id) {
        var param = [];
        var childnodes = table.querySelectorAll('tbody tr:not(.no-entries)');

        if (childnodes.length > 0) {
            for (var i = 0; i < childnodes.length; i++) {
                var data = {};
                var row = childnodes[i];
                var columns = row.querySelectorAll("td");
                data.job = id.toString();
                data.sku = columns[1].innerText;
                data.name = columns[2].innerText;
                data.price = columns[3].innerText;
                data.qty = columns[4].innerText;
                param.push(data);
            }

            // kirim data untuk menunjukan hasil pencarian
            const load_url = url('job/part');
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

                updateTableStatus('Sukses, data telah tersubmit', 'success')
            }, 'post', 'data='+JSON.stringify(param), true, false);
        }
    }
}

function updateTableStatus(text, tag = 'error') {
    // update status
    let status = $('#table-status')
    if (status) {
        status.innerText = text;

        // clear classlist
        status.className = '';

        if (tag === 'idle') {
            status.classList.add('text-muted')
        }

        if (tag === 'error') {
            status.classList.add('text-danger')
        }

        if (tag === 'success') {
            status.classList.add('text-success')
        }
    }
}