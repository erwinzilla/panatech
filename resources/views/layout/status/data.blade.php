@extends('layout.template')

@section('title', ucwords($title))

@section('sidebar')
    @include($config['blade'].'.sidebar')
@endsection

@section('content')
    @include('component.breadcrumb')

    {{--    Body--}}
    <div class="row mt-3">
        <div class="col-md-12">
            @include('component.alert')
            <div id="main" class="card">
                @include('component.card.header', ['type' => $type, 'title' => $type, 'desc' => 'daftar list warranty register', 'class' => 'd-flex justify-content-start w-100'])
                <div class="card-body p-0">
                    <div id="table-data">
                        @include($config['blade'].'.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection