@extends('template')

@section('title', 'User Privilege')

@section('sidebar')
    <div class="bg-white">
        @include('sidebar-profile')
        <ul class="nav nav-pills flex-column" id="sidebar">
            <li class="nav-item mb-1">
                <a href="{{ $type == 'data' ? '#main' : url('user/privilege') }}" class="nav-link" aria-current="page">@svg('heroicon-o-credit-card', 'icon') Privilege</a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ url('user/privilege/create') }}" class="nav-link">@svg('heroicon-o-plus', 'icon') Create new</a>
            </li>
{{--            Jika all access maka munculkan element--}}
            @if(Auth::user()->privileges->users > 2)
                <li class="nav-item">
                    <a href="{{ $type == 'trash' ? '#main' : url('user/privilege/trash') }}" class="nav-link text-danger">@svg('heroicon-o-trash', 'icon') Trash</a>
                </li>
            @endif
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
            <li class="breadcrumb-item"><a href="{{ url('user') }}" class="text-decoration-none">User</a></li>
            <li class="breadcrumb-item {{ $type != 'trash' ? 'active' : '' }}"><a href="{{ url('user/privilege') }}" class="text-decoration-none">Privilege</a></li>
            @if($type == 'trash')
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('user/privilege/trash') }}" class="text-decoration-none">Trash</a></li>
            @endif
        </ol>
    </nav>
    <h1 class="fw-bold">User Privilege</h1>

{{--    Body--}}
    <div class="row mt-3" data-bs-spy="scroll" data-bs-target="#sidebar" data-bs-smooth-scroll="true">
        <div class="col-md-12">
            @include('alert')
            <div id="main" class="card">
                <div class="card-header d-flex justify-content-start w-100">
                    @if($type == 'trash')
                        <div class="align-self-center me-3 text-danger">
                            @svg('heroicon-s-trash', 'icon', ['style' => 'width:40px;height:40px'])
                        </div>
                    @endif
                    <div>
                        <h4 class="fw-bold mb-0">{{ ucwords($type) }} List</h4>
                        <small class="text-muted">Daftar list hak ases pengguna</small>
                    </div>
{{--                Jika can CRUD maka munculkan tombol--}}
                    @if(Auth::user()->privileges->users > 1)
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
                                @if(Auth::user()->privileges->users > 1)
                                    <th class="pe-3 text-center">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
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
                                    @if(Auth::user()->privileges->users > 1)
                                        <td class="pe-3 w-2-slot">
                                            <div class="d-flex">
                                                @if($type == 'data')
                                                    <a href="{{ url('user/privilege/'.$row->id.'/edit') }}" class="btn btn-warning text-white me-2">@svg('heroicon-s-pencil-square', 'icon-sm')</a>
                                                    <form id="delete-{{ $row->id }}" method="post" action="{{ url('user/privilege/'.$row->id) }}" onsubmit="confirm_delete({{ $row->id }});return false;">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger text-white">@svg('heroicon-s-trash', 'icon-sm')</button>
                                                    </form>
                                                @endif
                                                @if($type == 'trash')
                                                    <a href="{{ url('user/privilege/restore/'.$row->id) }}" class="btn btn-outline-info me-2 restore-action-link">
                                                        @svg('heroicon-s-arrow-up-on-square', 'icon')
                                                    </a>
                                                    <a id="delete-link-{{ $row->id }}" href="{{ url('user/privilege/delete/'.$row->id) }}" class="btn btn-outline-danger delete-action-link" onclick="confirm_delete_link({{ $row->id }}); return false;">
                                                        @svg('heroicon-s-trash', 'icon')
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
