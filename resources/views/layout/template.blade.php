@extends('blank', ['class' => Auth::user()->theme.'-theme'])

@section('title')
    @yield('title')
@endsection

@section('main')
    @include('component.navbar')
    <div class="main-content" data-bs-spy="scroll" data-bs-target="#sidebar" data-bs-smooth-scroll="true">
        {{--    Sidebar --}}
        <section class="sidebar">
            @yield('sidebar')
        </section>

        {{--    Content--}}
        <section class="content pb-5">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>
    @include('layout.footer')
@endsection