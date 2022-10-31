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
                <div class="card-header d-flex justify-content-start w-100">
                    <div>
                        <h4 class="text-default fw-bold mb-0">Config</h4>
                        <small class="text-muted">Data konfigurasi sistem</small>
                    </div>
                    {{--                Jika can CRUD maka munculkan tombol--}}
                    @if(getUserLevel($config['privilege']) >= CAN_CRUD)
                        @if($type == 'data')
                            @if($data->count() > 0)
                                <div class="align-self-center ms-auto">
                                    <a href="{{ url($config['url'].'/'.$data->first()->id.'/edit') }}" class="btn btn-warning">
                                        @svg('heroicon-s-pencil-square', 'icon') Edit data
                                    </a>
                                </div>
                            @endif
                        @endif
                    @endif
                </div>
                <div class="card-body px-0 pb-5">
                    @if($data->count() > 0)
                        <table class="table table-striped">
                            <tr>
                                <td colspan="3">
                                    <b>Jobs</b>
                                </td>
                            </tr>
                            <tr>
                                <td>Update At</td>
                                <td>:</td>
                                <td>
                                    @if($data->first()->job_update_at)
                                        {{ date('d/m/Y', strtotime($data->first()->job_update_at)) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Update By</td>
                                <td>:</td>
                                <td>
                                    @if($data->first()->job_update_by)
                                        {{ $data->first()->job_users->name }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    @else
                        <div class="text-center">
                            <h5 class="text-default">Data belum pernah dibuat</h5>
                            <a href="{{ url($config['url'].'/create') }}" class="btn btn-primary">Generate Data</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection