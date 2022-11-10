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
            <small class="text-muted">Data ini yang nanti akan digunakan saat memberikan informasi tentang suku cadang</small>
            <br><small class="text-danger">*Wajib diisi</small>
        </div>
        <div id="main" class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
                    <input type="hidden" name="id" value="{{ old('id', $data->id) }}">
                    <div class="mb-3">
                        <label class="form-label">SKU / Part No.<span class="text-danger">*</span></label>
                        <input type="text" name="sku" class="form-control w-50 @error('sku') is-invalid @enderror" value="{{ old('sku', $data->sku) }}" placeholder="Masukan SKU / Nomor Kode Part" autofocus validate>
                        @error('sku')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
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
                        <label class="form-label">Price<span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control w-50 @error('price') is-invalid @enderror" value="{{ old('price', $data->price) }}" placeholder="Masukan harga" validate>
                        @error('price')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="loc" class="form-control w-50 @error('loc') is-invalid @enderror" value="{{ old('loc', $data->loc) }}" placeholder="Masukan Lokasi Part">
                        @error('loc')
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