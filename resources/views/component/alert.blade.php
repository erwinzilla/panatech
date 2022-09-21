<?php
if (session('status')) {
    $tag = 'alert-primary';
    $icon = 'information-circle';

    // jika sukses
    if (session('status') == 'success') {
        $tag = 'alert-success';
        $icon = 'check-circle';
    }

    // jika error
    if (session('status') == 'error') {
        $tag = 'alert-danger';
        $icon = 'x-circle';
    }

    // jika peringatan
    if (session('status') == 'warning') {
        $tag = 'alert-warning';
        $icon = 'exclamation-circle';
    }

    // jika informasi
    if (session('status') == 'info') {
        $tag = 'alert-info';
        $icon = 'information-circle';
    }
?>
<div class="alert {{ $tag }} alert-dismissible fade show mb-2 d-flex" role="alert">
    @svg('heroicon-s-'.$icon, 'icon opacity-75 mt-0')
    <div class="ms-2">
        <b>{{ ucfirst(session('status')) }}</b>
        <b class="mx-1">&#8226;</b>
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
<?php
}
?>
