@extends('template')

@section('title', 'Home')

@section('sidebar')
    <div class="bg-white">
        <div class="d-flex fs-6 mb-3">
            <img src="https://erwinzilla.com/v2/uploads/images/users/1642948549.jpg" class="avatar rounded-circle" alt="Avatar">
            <div class="mx-2">
                <b class="text-black">{{ ucwords(Auth::user()->name) }}</b><br>
                <span class="badge bg-primary bg-opacity-25 text-primary">Technician</span>
            </div>
            <span class="ms-auto align-self-center">@svg('heroicon-s-adjustments-horizontal', 'icon')</span>
        </div>
        <ul class="nav nav-pills flex-column">
            <li class="nav-item mb-1">
                <a class="nav-link active" aria-current="page" href="#">@svg('heroicon-o-home', 'icon') Home</a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link" aria-current="page" href="#">@svg('heroicon-o-list-bullet', 'icon') My Task</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">@svg('heroicon-o-clock', 'icon') Recent</a>
            </li>
        </ul>
        <hr class="border-dashed">
        <a href="#" class="btn btn-primary w-100 text-white">@svg('heroicon-s-clipboard-document-list', 'icon') New Ticket</a>
    </div>
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">@svg('heroicon-s-command-line', 'icon')</li>
            <li class="breadcrumb-item" aria-current="page">Home</li>
        </ol>
    </nav>
    <h1 class="fw-bold">Home</h1>
@endsection
