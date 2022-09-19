@extends('layout.template')

@section('title', ucwords($title))

@section('sidebar')
    @include('layout.user.sidebar')
@endsection

@section('content')
    <div class="section-body">
        <h5 class="section-title">Hi, {{ ucwords($data->name) }}!</h5>
        <p class="section-lead">
            Change information about yourself on this page.
        </p>
        <div class="row mt-sm-4">
            <div class="col-md-5">
                <div class="card profile-widget">
                    <div class="profile-widget-header">
                        @if($data->image)
                            <img alt="image" src="{{ url('uploads/images/users/'.$data->image) }}" class="rounded-circle profile-widget-picture">
                        @else
                            <img alt="image" src="{{ url('uploads/images/users/default.jpg') }}" class="rounded-circle profile-widget-picture">
                        @endif
                        <div class="profile-widget-items">
                            <div class="profile-widget-item">
                                <div class="profile-widget-item-label">Sales</div>
                                <div class="profile-widget-item-value text-default">187</div>
                            </div>
                            <div class="profile-widget-item">
                                <div class="profile-widget-item-label">Contri</div>
                                <div class="profile-widget-item-value text-default">6,8K</div>
                            </div>
                            <div class="profile-widget-item">
                                <div class="profile-widget-item-label">Privilege</div>
                                <div class="profile-widget-item-value text-default">{{ $data->privileges->name }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="profile-widget-description p-0 border-top">
                        <table class="table table-striped mb-0">
                            <tbody>
                            <tr>
                                <td class="w-1-slot text-center">
                                    @svg('heroicon-o-identification', 'icon')
                                </td>
                                <td class="ps-0">{{ $data->name }}</td>
                            </tr>
                            <tr>
                                <td class="w-1-slot text-center">
                                    @svg('heroicon-o-device-phone-mobile', 'icon')
                                </td>
                                <td class="ps-0">{{ $data->phone }}</td>
                            </tr>
                            <tr>
                                <td class="w-1-slot text-center">
                                    @svg('heroicon-o-envelope', 'icon')
                                </td>
                                <td class="ps-0"><a href="mailto:{{ $data->email }}" class="text-decoration-none">{{ $data->email }}</a></td>
                            </tr>
                            <tr>
                                <td class="w-1-slot text-center">
                                    @svg('heroicon-o-map-pin', 'icon')
                                </td>
                                <td class="ps-0">{{ $data->address }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <form action="{{ url('user/'.$data->id) }}" method="post" enctype="multipart/form-data">
                    @method('put')
                    @csrf
                    <div id="main" class="card mb-3">
                        <div class="card-header border-dashed-bottom">
                            <h5 class="text-black mb-0">Account</h5>
                            <small class="text-muted">Data ini yang nanti akan digunakan untuk membuat anda masuk ke dalam sistem</small>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 g-3">
                                <label class="form-label">Username<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted">{{ env('APP_DOMAIN').'/user/' }}</span>
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
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" value="" placeholder="Abaikan bila tidak diganti">
                                        @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label class="form-label">Password Confirmation<span class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" value="" placeholder="Abaikan bila tidak diganti">
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
                    <hr class="border-dashed">
                    <div id="profile" class="card mb-3">
                        <div class="card-header border-dashed-bottom">
                            <h5 class="text-black mb-0">Personal Information</h5>
                            <small class="text-muted">Informasi ini yang akan muncul di data pengguna / karyawan</small>
                        </div>
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
                        </div>
                    </div>
                    <hr class="border-dashed">
                    <div class="card mb-3">
                        <div class="card-body text-end">
                            @include('form.button.submit')
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
