@extends('layout.template')

@section('title', 'Home')

@section('sidebar')
    @include($config['blade'].'.sidebar')
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
