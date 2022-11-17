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
            <small class="text-muted">Data ini yang nanti akan digunakan saat memberikan informasi SABBR cabang servis</small>
            <br><small class="text-danger">*Wajib diisi</small>
        </div>
        <div id="main" class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
                    <input type="hidden" name="branch_service" value="{{ old('branch_service', $data->branch_service) }}">
                    <input type="hidden" name="id" value="{{ old('id', $data->id) }}">
                    <div class="mb-3">
                        <label class="form-label">Open<span class="text-danger">*</span></label>
                        <input type="number" name="open" class="form-control w-25 @error('open') is-invalid @enderror" value="{{ old('open', $data->open) }}" placeholder="Masukan nominal open set" validate>
                        @error('open')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Repair<span class="text-danger">*</span></label>
                        <input type="number" name="repair" class="form-control w-25 @error('repair') is-invalid @enderror" value="{{ old('repair', $data->repair) }}" placeholder="Masukan nominal repair set" validate>
                        @error('repair')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Complete<span class="text-danger">*</span></label>
                        <input type="number" name="complete" class="form-control w-25 @error('complete') is-invalid @enderror" value="{{ old('complete', $data->complete) }}" placeholder="Masukan nominal complete set" validate>
                        @error('complete')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Set Total<span class="text-danger">*</span></label>
                        <input type="number" name="set_total" class="form-control w-25 @error('set_total') is-invalid @enderror" value="{{ old('set_total', $data->set_total) }}" placeholder="Masukan nominal total set" validate>
                        @error('set_total')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
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