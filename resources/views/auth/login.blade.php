@extends('blank', ['class' => 'bg-login'])

@section('title', ucwords($title))

@section('main')
    @include('component.navbar.landing')
    <div class="container-fluid">
        <div class="row mt-md-4">
            <div class="col-md-4">
                <div class="card p-4 bg-transparent border-0">
                    <div class="card-body">
                        @if(env('APP_MAINTENANCE'))
                            <h1 class="fw-bold text-black">Sorry<b class="text-secondary">.</b></h1>
                            <h5 class="fw-bold">We Are Down For Maintenance</h5>
                            <img src="{{ url('assets/images/illustrations/maintenance.svg') }}" alt="maintenance" class="w-100">
                        @else
                            <h1 class="fw-bold text-black">Login Page<b class="text-secondary">.</b></h1>
                            <div class="mt-3">
                                <form action="{{ route('login') }}" method="post">
                                    @csrf
                                    <div class="mb-2">
                                        <div class="input-group input-group-lg @error('username') is-invalid @enderror">
                                            <div class="form-floating">
                                                <input id="username" type="text" name="username" class="form-control border-end-0 @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Username" autofocus>
                                                <label for="username">Username</label>
                                            </div>
                                            <span class="input-group-text text-muted">
                                                @svg('heroicon-s-identification', 'icon', ['style' => 'width:22px;height:22px'])
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="input-group input-group-lg @error('password') is-invalid @enderror">
                                            <div class="form-floating">
                                                <input id="password" type="password" name="password" class="form-control border-end-0 @error('password') is-invalid @enderror" value="" placeholder="password">
                                                <label for="password">Password</label>
                                            </div>
                                            <span class="input-group-text text-muted">
                                                @svg('heroicon-s-key', 'icon')
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" name="remember" id="remember">
                                            <label class="form-check-label" for="remember">Remember Me</label>
                                        </div>
                                    </div>
                                    @if(session('errors'))
                                        <div class="alert alert-danger alert-dismissible fade show mb-2 d-flex" role="alert">
                                            @svg('heroicon-s-x-circle', 'icon opacity-75 mt-0')
                                            <div class="ms-2">
                                                <b>Ada masalah saat login:</b>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                <ul class="mb-0 mt-1">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
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
                                    <div class="row g-2">
                                        <div class="col">
                                            <a href="#" class="btn btn-dark btn-lg w-100 fs-6" id="forgot">Forgot?</a>
                                        </div>
                                        <div class="col">
                                            <button name="login" type="submit" class="btn btn-lg btn-primary w-100 text-white fs-6" id="login">Login</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="mt-2">
                                    <small>Didn't Have an Account? <a href="#" class="text-decoration-none">SignUp</a> </small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection