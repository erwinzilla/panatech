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

    // table function
    let table_data_modal = $('#table-data-modal');
    if (table_data_modal) {
        // tambahkan fungsi jika ada perubahan pada search dan perpage selector
        table_data_modal.addEventListener('change', function (){
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

function choose(name, value) {
    let input = $('input[name="'+name+'"]');
    if (input && value) {
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

function send_http(url, callback, method = 'get', param = null, loading = true) {
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