// const base_url = 'https://erwinzilla.com/v2/';
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

// setting alert
let alert = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-primary btn-lg text-white me-2',
        cancelButton: 'btn btn-outline-primary btn-lg',
        popup: 'card',
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

    // table data
    let table_data = new JSTable('#table-data', {
        classes: {
            dropdown: "align-self-center",
            input: "form-control",
            search: "align-self-center",
            selector: "form-select",
            pagination: "pagination",
        },
        labels: {
            perPage: "<div class='d-flex justify-content-start align-items-center'>{select} <span class='ms-2 w-100'>entries per page</span></div>",
            loading: "<div class='spinner-border spinner-border-sm text-primary' role='status' aria-hidden='true'></div><span class='ms-2'>Loading...</span>",
        },
        // serverSide: false,
        // ajax: '/user/privilege/table',
        // ajaxParams: {
        //     type : $('#table-data').dataset.type
        // }
    });
});

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
        let input_name = $('#name');
        if (input_name) {
            input_name.innerText = opt_name;
        }
    }

    // update privilege badge
    if (opt_privilege && opt_privilege_color) {
        let input_privilege = $('#privilege');
        if (input_privilege) {
            input_privilege.className = "badge bg-"+opt_privilege_color+" text-"+opt_privilege_color+"  bg-opacity-25";
            input_privilege.innerText = opt_privilege;
        }
    }
}