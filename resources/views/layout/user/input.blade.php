@extends('template')

@section('title', ucwords($title))

@section('sidebar')
    @include('layout.user.sidebar')
@endsection

@section('content')
    @include('breadcrumb')
    <h1 class="fw-bold">{{ ucwords($title) }}</h1>

    {{--    Body--}}
    <div class="row mt-3" data-bs-spy="scroll" data-bs-target="#sidebar" data-bs-smooth-scroll="true">
        <div class="col-md-3">
            <h5 class="text-black mb-0">Account</h5>
            <small class="text-muted">Data ini yang nanti akan digunakan untuk membuat pengguna dapat masuk ke dalam sistem</small>
        </div>
        <div id="main" class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
                    @if($type == 'create')
                        <form action="{{ url('user') }}" method="post">
                    @endif
                    @if($type == 'edit')
                        <form action="{{ url('user/'.$data->id) }}" method="post">
                        @method('put')
                    @endif
                    @csrf
                    <div class="mb-3 g-3">
                        <label class="form-label">Username<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted">{{ env('APP_DOMAIN').'/user/' }}</span>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $data->username) }}" placeholder="Masukan username">
                        </div>
                        <small class="text-info">
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
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $data->email) }}" placeholder="Mis. erwin.ganteng@erwinzilla.com">
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
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password', $data->password) }}" placeholder="Masukan password">
                                @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="form-label">Re-Password<span class="text-danger">*</span></label>
                                <input type="password" name="pass2nd" class="form-control @error('pass2nd') is-invalid @enderror" value="{{ old('pass2nd') }}" placeholder="Masukan kembali password">
                                @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        @include('form.button', ['name' => 'account'])
                    </div>
                </div>
                </form>
            </div>
        </div>
        <div class="col-md-12">
            <hr class="border-dashed">
        </div>
        <div class="col-md-3">
            <h5 class="text-black mb-0">Personal Information</h5>
            <small class="text-muted">Informasi ini yang akan muncul di data pengguna / karyawan</small>
        </div>
        <div id="profile" class="col-md-9">
            <form class="card mb-3">
                <div class="card-body">
                    @if($type == 'create')
                        <form action="{{ url('user') }}" method="post">
                    @endif
                    @if($type == 'edit')
                        <form action="{{ url('user/'.$data->id) }}" method="post">
                        @method('put')
                    @endif
                    @csrf
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
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        @include('form.button', ['name' => 'personal'])
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
