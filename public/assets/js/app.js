// const base_url = 'https://erwinzilla.com/v2/';

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
