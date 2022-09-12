// const base_url = 'https://erwinzilla.com/v2/';

// setting alert
let alert = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-primary btn-lg text-white',
        cancelButton: 'btn btn-default btn-lg',
        popup: 'card',
        title: 'text-default'
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
