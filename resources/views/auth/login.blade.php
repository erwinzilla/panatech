<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

{{--    Bootstrap 5.1 custom css--}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Righteous|Poppins">
    <link href="{{ url('assets/css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css"></head>
<body class="bg-login">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><h1 class="font-logo">PANA<span class="text-primary">TECH</span><span class="text-secondary">.</span></h1></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-start" id="navbarNav">
                <ul class="navbar-nav h5">
                    <li class="nav-item me-3">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="#">Features</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="#">Pricing</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link disabled">Disabled</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card p-4 bg-transparent">
                    <div class="card-body">
                        <h1 class="fw-bold text-black">Login Page<b class="text-secondary">.</b></h1>
                        <div class="mt-3">
                            <form action="{{ route('login') }}" method="post">
                                @csrf
                                <div class="mb-2">
                                    <label class="small" for="username">Username / Email</label>
                                    <div class="input-group input-group-lg">
                                        <input type="text" name="username" class="form-control border-end-0" id="username" value="" required autofocus>
                                        <span class="input-group-text text-muted">
                                            <i class="bi-envelope-fill"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="small" for="username">Password</label>
                                    <div class="input-group input-group-lg">
                                        <input type="password" name="password" class="form-control border-end-0" id="password" value="" required>
                                        <span class="input-group-text text-muted">
                                            <i class="bi-key-fill"></i>
                                        </span>
                                    </div>
                                </div>
                                @if(session('errors'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        Something it's wrong:
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if (Session::has('success'))
                                    <div class="alert alert-success">
                                        {{ Session::get('success') }}
                                    </div>
                                @endif
                                @if (Session::has('error'))
                                    <div class="alert alert-danger">
                                        {{ Session::get('error') }}
                                    </div>
                                @endif
                                <div class="mb-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" name="remember" id="remember">
                                        <label class="form-check-label" for="remember">Remember Me</label>
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col">
                                        <a href="#" class="btn btn-dark btn-lg w-100 fs-6" id="forgot">Forgot?</a>
                                    </div>
                                    <div class="col">
                                        <button name="login" type="submit" class="btn btn-lg btn-primary w-100 text-white fs-6" id="login">Log In</button>
                                    </div>
                                </div>
                            </form>
                            <div class="mt-2">
                                <small>Didn't Have an Account? <a href="#" class="text-decoration-none">SignUp</a> </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>
</html>
