@extends('layout.template')

@section('title', ucwords($title))

@section('sidebar')
    @include($config['blade'].'.sidebar')
@endsection

@section('content')
    @include('component.breadcrumb')

    @include('form.header.start')
    {{--    Body--}}
    <div class="row mt-3">
        <div class="col-md-3">
            <h5 class="text-black mb-0">Information</h5>
            <small class="text-muted">Data ini yang nanti akan digunakan saat memberikan informasi konsumen</small>
            <br><small class="text-danger">*Wajib diisi</small>
        </div>
        <div id="main" class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
                    <input type="hidden" name="id" value="{{ old('id', $data->id) }}">
                    <div class="mb-3">
                        <label class="form-label">Name<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control w-50 @error('name') is-invalid @enderror" value="{{ old('name', $data->name) }}" placeholder="Nama Konsumen" validate>
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone<span class="text-danger">*</span></label>
                        <div class="d-flex">
                            <div class="w-50">
                                <input type="number" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $data->phone) }}" placeholder="Nomor telepon" validate>
                                @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <button class="btn btn-primary ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePhone" aria-expanded="false" aria-controls="collapsePhone">@svg('heroicon-s-phone', 'icon-sm') Add Another</button>
                        </div>
                    </div>
                    <div class="mb-3 collapse" id="collapsePhone">
                        <div class="row">
                            <div class="col">
                                <label class="form-label">Mobile Phone</label>
                                <input type="number" name="phone2" class="form-control @error('phone2') is-invalid @enderror" value="{{ old('phone2', $data->phone2) }}" placeholder="Boleh dikosongkan">
                                @error('phone2')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="form-label">Business Phone</label>
                                <input type="number" name="phone3" class="form-control @error('phone3') is-invalid @enderror" value="{{ old('phone3', $data->phone3) }}" placeholder="Boleh dikosongkan">
                                @error('phone3')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control w-50 @error('address') is-invalid @enderror" placeholder="Alamat Konsumen">{{ old('address', $data->address) }}</textarea>
                        @error('address')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control w-50 @error('email') is-invalid @enderror" value="{{ old('email', $data->email) }}" placeholder="Email Konsumen">
                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type<span class="text-danger">*</span></label>
                        <div>
                            @foreach($data_additional as $row)
                                <input type="radio" class="btn-check" name="type" id="option-type-{{ $row->id }}" value="{{ $row->id }}" {{ old('type', $data->type) == $row->id ? 'checked' : ''}}>
                                <label class="btn btn-outline-primary rounded-5" for="option-type-{{ $row->id }}">{{ $row->name }}</label>
                            @endforeach
                        </div>
                        @error('type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tax Id</label>
                        <input type="text" name="tax_id" class="form-control w-50 @error('tax_id') is-invalid @enderror" value="{{ old('tax_id', $data->tax_id) }}" placeholder="Masukan No. NPWP / NIK">
                        @error('tax_id')
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
        </div>
    </div>
    @include('form.header.end')
@endsection

@section('script')
    <script>
        initInput('{{ $config['url'] }}');
    </script>
@endsection