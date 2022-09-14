@extends('template')

@section('title', 'Home')

@section('sidebar')
    <div class="bg-white">
        @include('sidebar.profile')
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
