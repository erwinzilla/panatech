@extends('layout.template')

@section('title', ucwords($title))

@section('sidebar')
    @include($config['blade'].'.sidebar')
@endsection

@section('content')
    @include('component.breadcrumb')

    {{--    Body--}}
    @include('form.header.start')
    <div class="row mt-3">
        <div class="col-md-3">
            <h5 class="text-black mb-0">Main Menu</h5>
            <small class="text-muted">Data ini yang nanti akan digunakan saat memberikan akses kepada pengguna</small>
            <br><small class="text-danger">*Wajib diisi</small>
        </div>
        <div id="main" class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Name<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control w-50 @error('name') is-invalid @enderror" value="{{ old('name', $data->name) }}" placeholder="Nama Hak Akses / Privilege">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <hr class="border-dashed">
                    <div class="mb-3">
                        @include('form.privilege', ['name' => 'tickets'])
                    </div>
                    <div class="mb-3">
                        @include('form.privilege', ['name' => 'customers'])
                    </div>
                    <div class="mb-3">
                        @include('form.privilege', ['name' => 'products'])
                    </div>
                    <div class="mb-3">
                        @include('form.privilege', ['name' => 'reports'])
                    </div>
                    <div class="mb-3">
                        @include('form.privilege', ['name' => 'users'])
                    </div>
                    <div class="mb-3">
                        @include('form.privilege', ['name' => 'branches'])
                    </div>
                    <hr class="border-dashed">
                    <div class="mb-3">
                        @include('form.color')
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        @include('form.button.submit')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('form.header.end')
@endsection
