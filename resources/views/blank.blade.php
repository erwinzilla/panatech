<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @include('favicon')
    {{-- Bootstrap 5.1 custom css--}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Righteous|Poppins">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.2.0/dist/css/datepicker.min.css">
    <link rel="stylesheet" href="{{ url('assets/css/app.min.css') }}">
    {{--    @vite('resources/css/app.css')--}}
</head>
<body class="{{ isset($class) ? $class : '' }}">

@yield('main')

{{--    Place for script--}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.2.0/dist/js/datepicker.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/app.js') }}" url="{{ url('') }}"></script>

@yield('script')

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