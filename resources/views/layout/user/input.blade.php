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
            <h5 class="text-black mb-0">Account</h5>
            <small class="text-muted">Data ini yang nanti akan digunakan untuk membuat pengguna dapat masuk ke dalam sistem</small>
            <br><small class="text-danger">*Wajib diisi</small>
        </div>
        <div id="main" class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3 g-3">
                        <label class="form-label">Username<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text text-default">{{ env('APP_DOMAIN').'/user/' }}</span>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $data->username) }}" placeholder="Masukan username">
                        </div>
                        <small class="text-info form-text">
                            @svg('heroicon-o-information-circle', 'icon-sm')
                            Username ini juga yang nanti digunakan saat login
                        </small>
                        @error('username')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email<span class="text-danger">*</span></label>
                        <div class="row g-3">
                            <div class="col">
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $data->email) }}" placeholder="mis. erwin.ganteng@erwinzilla.com">
                            </div>
                            <div class="col {{ $data->email_verified_at ? 'text-primary' : 'text-muted' }} d-flex align-self-center">
                                @svg('heroicon-s-check-badge', 'icon me-1') {{ $data->email_verified_at ? 'Verified' : 'Not Verified' }}
                            </div>
                        </div>
                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="row g-3">
                            <div class="col">
                                <label class="form-label">Password<span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" placeholder="{{ $type == 'create' ? 'Masukan password' : 'Kosongkan bila tidak diganti' }}">
                                @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="form-label">Password Confirmation<span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" value="{{ old('password_confirmation') }}" placeholder="{{ $type == 'create' ? 'Masukan password' : 'Kosongkan bila tidak diganti' }}">
                                @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <hr class="border-dashed">
        </div>
        <div class="col-md-3">
            <h5 class="text-black mb-0">Personal Information</h5>
            <small class="text-muted">Informasi ini yang akan muncul di data pengguna / karyawan</small>
            <br><small class="text-danger">*Wajib diisi</small>
        </div>
        <div id="profile" class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Name<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control w-50 @error('name') is-invalid @enderror" value="{{ old('name', $data->name) }}" placeholder="Nama pengguna / karyawan">
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Photo</label>
                        <div class="d-flex">
                            @if($data->image)
                                <img src="{{ asset('uploads/images/users/'.$data->image) }}" class="avatar rounded-circle">
                            @else
                                <img src="{{ asset('uploads/images/users/default.jpg') }}" class="avatar rounded-circle">
                            @endif
                            <div class="ms-3 align-self-center">
                                <label class="btn btn-outline-primary">
                                    Change <input name="image" type="file" class="form-control" id="formFile" value="{{ $data->image }}" hidden>
                                </label>
                            </div>
                        </div>
                        @error('image')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" placeholder="mis. Bandung, Jawa Barat">{{ trim(old('address', $data->address)) }}</textarea>
                        @error('address')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="number" name="phone" class="form-control w-50 @error('phone') is-invalid @enderror" value="{{ old('phone', $data->phone) }}" placeholder="Nomor telepon pengguna / karyawan">
                        @error('phone')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Privilege<span class="text-danger">*</span></label>
                        <div>
                            @foreach($data_additional['user_privilege'] as $row)
                                <input type="radio" class="btn-check" name="privilege" id="option-privilege-{{ $row->id }}" value="{{ $row->id }}" {{ old('privilege', $data->privilege) == $row->id ? 'checked' : ''}}>
                                <label class="btn btn-outline-{{ $row->color }} rounded-5 mb-1" for="option-privilege-{{ $row->id }}">{{ $row->name }}</label>
                            @endforeach
                        </div>
                        @error('privilege')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Branch Service<span class="text-danger">*</span></label>
                        <select name="branch_service" class="form-select w-25 @error('branch_service') is-invalid @enderror">
                            @foreach($data_additional['branch_service'] as $row)
                                <option value="{{ $row->id }}" {{ $row->id == old('branch_service', $data->branch_service) ? 'selected' : '' }}>{{ $row->code }}</option>
                            @endforeach
                        </select>
                        @error('branch_service')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <hr class="border-dashed">
        </div>
        <div class="col-md-3">
            <h5 class="text-black mb-0">Submit Information</h5>
            <small class="text-muted">Kirim semua data yang telah terisi</small>
        </div>
        <div class="col-md-9 mb-3 text-end">
            <div class="card">
                <div class="card-body text-end">
                    @include('form.button.submit')
                </div>
            </div>
        </div>
    </div>
    @include('form.header.end')
@endsection
