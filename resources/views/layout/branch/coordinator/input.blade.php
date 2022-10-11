@extends('layout.template')

@section('title', ucwords($title))

@section('sidebar')
    @include($config['blade'].'.sidebar')
@endsection

@section('content')
    @include('component.breadcrumb')

    {{--    Body--}}
    <div class="row mt-3">
        <div class="col-md-3">
            <h5 class="text-black mb-0">Main Menu</h5>
            <small class="text-muted">Data ini yang nanti akan digunakan saat memberikan seorang koordinator cabang</small>
            <br><small class="text-danger">*Wajib diisi</small>
        </div>
        <div id="main" class="col-md-9">
            @include('form.header.start')
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
                            <button type="button" class="btn btn-primary me-3 btn-table-modal" data-bs-toggle="modal" data-bs-target="#table-modal" data-target="user">@svg('heroicon-s-user', 'icon') Select User</button>
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
            @include('form.header.end')
        </div>
    </div>

    @include('component.modal.table')
@endsection
