const base_url = 'http://127.0.0.1:8000/';
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
        // tambahkan fungsi jika ada perubahan pada search dan perpage selector
        table_data.addEventListener('change', function (){
            let perPage = $('select[name="perPage"]').value;
            let search = $('input[name="search"]').value;

            let params = 'perPage=' + perPage +
                '&search=' + search +
                '&target=table';

            // kirim data untuk menunjukan hasil pencarian
            let load_url = window.location.href + '?' + params;
            send_http(load_url, function (data) {
                table_data.innerHTML = data;
            });

            init();
        });

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
                    let load_url = url(target+'/choose?target=table');
                    send_http(load_url, function (data) {
                        table_data_modal.innerHTML = data;
                        table_data_modal.dataset.url = url(target+'/choose');
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
            let perPage = $('select[name="perPage"]').value;
            let search = $('input[name="search"]').value;

            let params = 'perPage=' + perPage +
                '&search=' + search +
                '&target=table';

            // kirim data untuk menunjukan hasil pencarian
            let load_url = table_data_modal.dataset.url + '?' + params;
            send_http(load_url, function (data) {
                table_data_modal.innerHTML = data;
            });

            init();
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

function choose(name, value, opt_name = null, opt_privilege = null, opt_privilege_color = null) {
    let input = $('input[name="'+name+'"]');
    if (input && value) {
        input.value = value
    }

    // update nama user
    if (opt_name) {
        let input_name = $('#name-'+name);
        if (input_name) {
            input_name.innerText = opt_name;
        }
    }

    // update privilege badge
    if (opt_privilege && opt_privilege_color) {
        let input_privilege = $('#privilege-'+name);
        if (input_privilege) {
            input_privilege.className = "badge bg-"+opt_privilege_color+" text-"+opt_privilege_color+"  bg-opacity-25";
            input_privilege.innerText = opt_privilege;
        }
    }
}

function send_http(url, callback, method = 'get', param = null, loading = true) {
    console.log(url);
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
            imageUrl: '/assets/images/loading.svg',
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
        http.open(method, url);
        http.send()
    }

    if (method === 'post' || method === 'put') {
        http.open(method, url, true);
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
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