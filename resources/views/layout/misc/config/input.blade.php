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
            <small class="text-muted">Data ini yang nanti akan digunakan saat mengatur sistem</small>
            <br><small class="text-danger">*Wajib diisi</small>
        </div>
        <div id="main" class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="text-default fw-bold">Jobs</h5>
                    <div class="mb-3">
                        <label class="form-label">Job Update At</label>
                        <input type="date" name="job_update_at" class="form-control w-50 @error('job_update_at') is-invalid @enderror" value="{{ date('Y-m-d', strtotime(old('job_update_at', $data->job_update_at))) }}" placeholder="Masukan tanggal update job">
                        @error('job_update_at')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <h5 class="text-default fw-bold mt-3">Invoices</h5>
                    <div class="mb-3">
                        <label class="form-label">Invoice Job Status</label>
                        <select name="invoice_job_status" class="form-select w-50 @error('invoice_job_status') is-invalid @enderror">
                            @foreach($data_additional as $row)
                                <option value="{{ $row->id }}" {{ old('invoice_job_status', $data->invoice_job_status) == $row->id ? 'selected' : ''}}>{{ $row->name }}</option>
                            @endforeach
                        </select>
                        @error('invoice_job_status')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Invoice Job Status Invoice</label>
                        <select name="invoice_job_status_invoice" class="form-select w-50 @error('invoice_job_status_invoice') is-invalid @enderror">
                            @foreach($data_additional as $row)
                                <option value="{{ $row->id }}" {{ old('invoice_job_status', $data->invoice_job_status_invoice) == $row->id ? 'selected' : ''}}>{{ $row->name }}</option>
                            @endforeach
                        </select>
                        @error('invoice_job_status_invoice')
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

    @include('component.modal.table')
@endsection