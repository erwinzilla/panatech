@extends('template')

@section('title', 'User Privilege')

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
        <ul class="nav nav-pills flex-column" id="sidebar">
            <li class="nav-item mb-1">
                <a href="{{ $type == 'data' ? '#main' : url('user/privilege') }}" class="nav-link" aria-current="page">@svg('heroicon-o-credit-card', 'icon') Privilege</a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ url('user/privilege/create') }}" class="nav-link">@svg('heroicon-o-plus', 'icon') Create new</a>
            </li>
            <li class="nav-item">
                <a href="{{ $type == 'trash' ? '#main' : url('user/privilege/trash') }}" class="nav-link text-danger">@svg('heroicon-o-trash', 'icon') Trash</a>
            </li>
        </ul>
        <hr class="border-dashed">
        <a href="#" class="btn btn-primary w-100 text-white">@svg('heroicon-s-clipboard-document-list', 'icon') New Ticket</a>
    </div>
@endsection

@section('content')
{{--    Breadcrumb--}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">@svg('heroicon-s-command-line', 'icon')</li>
            <li class="breadcrumb-item" aria-current="page">User</li>
            <li class="breadcrumb-item active" aria-current="page">Privilege</li>
        </ol>
    </nav>
    <h1 class="fw-bold">User Privilege</h1>

{{--    Body--}}
    <div class="row mt-3" data-bs-spy="scroll" data-bs-target="#sidebar" data-bs-smooth-scroll="true">
        <div class="col-md-12">
            @include('alert')
            <div id="main" class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <h4 class="fw-bold mb-0">List</h4>
                        <small class="text-muted">Daftar list hak ases pengguna</small>
                    </div>
                    <div class="align-self-center">
                        <a href="{{ url('user/privilege/create') }}" class="btn btn-primary text-white">
                            @svg('heroicon-s-plus', 'icon') Create new
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Name</th>
                                <th>Color</th>
                                <th class="pe-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                                <tr class="align-middle">
                                    <td class="ps-3 text-muted">{{ $loop->iteration }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td>
                                        @if($row->color)
                                            <span class="badge bg-{{ $row->color }} text-{{ $row->color }} bg-opacity-25">{{ ucwords($row->color) }}</span>
                                        @else
                                            <span>Unset</span>
                                        @endif
                                    </td>
                                    <td class="pe-3 w-2-slot">
                                        <div class="d-flex">
                                            <a href="{{ url('user/privilege/'.$row->id.'/edit') }}" class="btn btn-warning text-white me-2">@svg('heroicon-s-pencil-square', 'icon-sm')</a>
                                            <form id="delete-{{ $row->id }}" method="post" action="{{ url('user/privilege/'.$row->id) }}" onsubmit="confirm_delete({{ $row->id }});return false;">
                                                @method('delete')
                                                @csrf
                                                <button type="submit" class="btn btn-danger text-white">@svg('heroicon-s-trash', 'icon-sm')</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
