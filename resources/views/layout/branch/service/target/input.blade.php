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
            <small class="text-muted">Data ini yang nanti akan digunakan saat memberikan informasi target cabang servis</small>
            <br><small class="text-danger">*Wajib diisi</small>
        </div>
        <div id="main" class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
                    <input type="hidden" name="branch_service" value="{{ old('branch_service', $data->branch_service) }}">
                    <input type="hidden" name="id" value="{{ old('id', $data->id) }}">
                    <div class="mb-3">
                        <label class="form-label">Incentive<span class="text-danger">*</span></label>
                        <div class="input-group has-validation w-50">
                            <span class="input-group-text rounded-start rounded-0">Rp.</span>
                            <input type="number" name="incentive" class="form-control @error('incentive') is-invalid @enderror" value="{{ old('incentive', $data->incentive) }}" placeholder="Masukan insentif" validate>
                            @error('incentive')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Income Target<span class="text-danger">*</span></label>
                        <div class="input-group has-validation w-50">
                            <span class="input-group-text rounded-start rounded-0">Rp.</span>
                            <input type="number" name="income_target" class="form-control @error('income_target') is-invalid @enderror" value="{{ old('income_target', $data->income_target) }}" placeholder="Masukan income target" validate>
                            @error('income_target')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Income Dividen<span class="text-danger">*</span></label>
                        <div class="input-group has-validation w-25">
                            <input type="number" name="income_div" class="form-control @error('income_div') is-invalid @enderror rounded-start rounded-0" value="{{ old('income_div', $data->income_div) }}" placeholder="Income dividen" validate>
                            <span class="input-group-text">%</span>
                            @error('income_div')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Speed Repair Target<span class="text-danger">*</span></label>
                        <div class="input-group has-validation w-25">
                            <input type="number" name="speed_repair_target" class="form-control @error('speed_repair_target') is-invalid @enderror rounded-start rounded-0" value="{{ old('speed_repair_target', $data->speed_repair_target) }}" placeholder="Speed target" validate>
                            <span class="input-group-text">%</span>
                            @error('speed_repair_target')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Speed Repair Dividen<span class="text-danger">*</span></label>
                        <div class="input-group has-validation w-25">
                            <input type="number" name="speed_repair_div" class="form-control @error('speed_repair_div') is-invalid @enderror rounded-start rounded-0" value="{{ old('speed_repair_div', $data->speed_repair_div) }}" placeholder="Speed dividen" validate>
                            <span class="input-group-text">%</span>
                            @error('speed_repair_div')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SABBR Target<span class="text-danger">*</span></label>
                        <div class="input-group has-validation w-25">
                            <input type="number" name="sabbr_target" class="form-control @error('sabbr_target') is-invalid @enderror rounded-start rounded-0" value="{{ old('sabbr_target', $data->sabbr_target) }}" placeholder="SABBR target" validate>
                            <span class="input-group-text">%</span>
                            @error('sabbr_target')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SABBR Dividen<span class="text-danger">*</span></label>
                        <div class="input-group has-validation w-25">
                            <input type="number" name="sabbr_div" class="form-control @error('sabbr_div') is-invalid @enderror rounded-start rounded-0" value="{{ old('sabbr_div', $data->sabbr_div) }}" placeholder="SABBR dividen" validate>
                            <span class="input-group-text">%</span>
                            @error('sabbr_div')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SABBR Max Result<span class="text-danger">*</span></label>
                        <div class="input-group has-validation w-25">
                            <input type="number" name="sabbr_max_result" class="form-control @error('sabbr_max_result') is-invalid @enderror rounded-start rounded-0" value="{{ old('sabbr_max_result', $data->sabbr_max_result) }}" placeholder="SABBR Max" validate>
                            <span class="input-group-text">%</span>
                            @error('sabbr_max_result')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
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