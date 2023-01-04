<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Generate QC Label</title>
    @include('favicon')
    <link rel="stylesheet" href="{{ url('assets/css/app.min.css') }}">
</head>
<body>

<div class="container mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <span>Nomor tiket yang tersedia</span>
                    <h1 class="fw-bold text-default mb-3">{{ $data->name }}</h1>
                    <a href="{{ url('ticket/generateProcess/'.$data->name) }}" class="btn btn-primary btn-lg">Book In</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="{{ asset('assets/js/app.js') }}" url="{{ url('') }}"></script>
@if(session('status'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            //show alert
            alert.fire({
                title: '{{ ucwords(session('status')) }}',
                text: '{{ session('message') }}',
                icon: '{{ session('status') }}',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false
            })
        })
    </script>
@endif
</body>
</html>