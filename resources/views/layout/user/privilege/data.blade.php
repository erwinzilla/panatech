@extends('layout.template')

@section('title', ucwords($title))

@section('sidebar')
    @include('layout.user.privilege.sidebar')
@endsection

@section('content')
    @include('component.breadcrumb')
    <h1 class="fw-bold">{{ ucwords($title) }}</h1>

{{--    Body--}}
    <div class="row mt-3">
        <div class="col-md-12">
            @include('component.alert')
            <div id="main" class="card">
                <div class="card-header d-flex justify-content-start w-100">
                    @if($type == 'trash')
                        <div class="align-self-center me-3 text-danger">
                            @svg('heroicon-s-trash', 'icon', ['style' => 'width:40px;height:40px'])
                        </div>
                    @endif
                    <div>
                        <h4 class="fw-bold mb-0">{{ ucwords($type) }}</h4>
                        <small class="text-muted">Daftar list hak ases pengguna</small>
                    </div>
{{--                Jika can CRUD maka munculkan tombol--}}
                    @if(getUserLevel('users') >= CAN_CRUD)
                        <div class="align-self-center ms-auto">
                            @if($type == 'data')
                                <a href="{{ url('user/privilege/create') }}" class="btn btn-primary">
                                    @svg('heroicon-s-plus', 'icon') Create new
                                </a>
                            @endif
                            @if($type == 'trash')
                                <a href="{{ url('user/privilege/restore') }}" class="btn btn-info me-2 restore-action-link">
                                    @svg('heroicon-s-arrow-up-on-square-stack', 'icon') Restore All
                                </a>
                                <a href="{{ url('user/privilege/delete') }}" class="btn btn-danger delete-action-link">
                                    @svg('heroicon-s-trash', 'icon') Destroy All
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Name</th>
                                <th>Color</th>
{{--                                Jika can CRUD maka munculkan tombol--}}
                                @if(getUserLevel('users') >= CAN_CRUD)
                                    <th class="pe-3 text-center">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if($data->count() > 0)
                                @foreach($data as $row)
                                    <tr class="align-middle">
                                        <td class="ps-3 text-muted w-1-slot">{{ $loop->iteration }}</td>
                                        <td>{{ $row->name }}</td>
                                        <td>
                                            @if($row->color)
                                                <span class="badge bg-{{ $row->color }} text-{{ $row->color }} bg-opacity-25">{{ ucwords($row->color) }}</span>
                                            @else
                                                <span>Unset</span>
                                            @endif
                                        </td>
    {{--                                    Jika can CRUD maka munculkan tombol--}}
                                        @if(getUserLevel('users') >= CAN_CRUD)
                                            <td class="pe-3 w-2-slot">
                                                <div class="d-flex justify-content-center">
                                                    @include('form.button.crud', ['url' => 'user/privilege/', 'type' => $type, 'id' => $row->id])
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    @php
                                    $colspan = 3;
                                    if (getUserLevel('users') >= CAN_CRUD) {
                                        $colspan += 1; // ada baguian untuk action button
                                    }
                                    @endphp
                                    <td class="text-muted text-center" colspan="{{ $colspan }}">Tidak Ada Data</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
