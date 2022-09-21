@extends('layout.template')

@section('title', ucwords($title))

@section('sidebar')
    @include('layout.branch.coordinator.sidebar')
@endsection

@section('content')
    @include('component.breadcrumb')
    <h1 class="fw-bold">{{ ucwords($title) }}</h1>

    {{--    Body--}}
    <div class="row mt-3">
        <div class="col-md-3">
            <h5 class="text-black mb-0">Main Menu</h5>
            <small class="text-muted">Data ini yang nanti akan digunakan saat memberikan seorang koordinator cabang</small>
        </div>
        <div id="main" class="col-md-9">
            @if($type == 'create')
                <form action="{{ url('branch/coordinator') }}" method="post">
            @endif
            @if($type == 'edit')
                <form action="{{ url('branch/coordinator/'.$data->id) }}" method="post">
                @method('put')
            @endif
                @csrf
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control w-50 @error('name') is-invalid @enderror" value="{{ old('name', $data->name) }}" placeholder="Nama Cabang">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Coodinator</label>
                            <input type="hidden" name="user" value="{{ old('user', $data->user) }}">
                            <div class="d-flex justify-content-start">
                                <button type="button" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#user-modal">@svg('heroicon-s-user', 'icon') Select User</button>
                                <div>
                                    <span id="name">{{ $data->user ? $data->users->name : 'Select user first'}}</span>
                                    <br><span id="privilege" class="{{ $data->user ? getBadge($data->users->privileges->color) : '' }}">{{ $data->user ? $data->users->privileges->name : '-' }}</span>
                                </div>
                            </div>
                            @error('user')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            @include('form.button.submit')
                        </div>
                    </div>
                </div>
                </form>
        </div>
    </div>

{{--    Modal User--}}
    <div class="modal fade" id="user-modal" tabindex="-1" aria-labelledby="user-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="user-modal-label">User</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name / Detail</th>
                            <th>Privilege</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data2 as $row)
                            <tr class="align-middle">
                                <td class="w-1-slot text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <b>{{ $row->name }}</b>
                                    <br><small>{{ $row->phone }}</small>
                                    <br><small class="text-muted">{{ $row->address }}</small>
                                </td>
                                <td>
                                    <span class="{{ getBadge($row->privileges->color) }}">{{ $row->privileges->name }}</span>
                                </td>
                                <td class="w-1-slot text-center">
                                    <button type="button" class="btn btn-success btn-icon" data-bs-toggle="tooltip" data-bs-title="Choose" onclick="choose('user', {{ $row->id }}, '{{ $row->name }}', '{{ $row->privileges->name }}', '{{ $row->privileges->color }}');return false;" data-bs-dismiss="modal">
                                        @svg('heroicon-s-check-circle', 'icon')
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection