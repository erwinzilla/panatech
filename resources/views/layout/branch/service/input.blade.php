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
            <small class="text-muted">Data ini yang nanti akan digunakan saat memberikan informasi cabang servis</small>
        </div>
        <div id="main" class="col-md-9">
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
                        <label class="form-label">Code<span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control w-25 @error('code') is-invalid @enderror" value="{{ old('code', $data->code) }}" placeholder="mis. 3501">
                        @error('code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address<span class="text-danger">*</span></label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" placeholder="mis. Jl. Kopo Gg. Pabrik Kulit Utara, Bandung">{{ old('address', $data->address) }}</textarea>
                        @error('address')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <textarea name="phone" class="form-control w-50 @error('phone') is-invalid @enderror" placeholder="mis. 081234567890">{{ old('phone', $data->phone) }}</textarea>
                        @error('phone')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fax</label>
                        <input type="text" name="fax" class="form-control w-50 @error('fax') is-invalid @enderror" value="{{ old('fax', $data->fax) }}" placeholder="mis. 0231-232183">
                        @error('fax')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <textarea name="email" class="form-control w-50 @error('email') is-invalid @enderror" placeholder="mis. service.cirebon@service-gdn.co.id">{{ old('email', $data->email) }}</textarea>
                        @error('email')
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
            <h5 class="text-black mb-0">Branch</h5>
            <small class="text-muted">Data pengurus dan pencabangan dari mana servis ini</small>
        </div>
        <div id="branch" class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Head Service</label>
                        <input type="hidden" name="user" value="{{ old('user', $data->user) }}">
                        <div class="d-flex justify-content-start">
                            <button type="button" class="btn btn-primary me-3 btn-table-modal" data-bs-toggle="modal" data-bs-target="#table-modal" data-target="user">@svg('heroicon-s-user', 'icon') Select User</button>
                            <div>
                                <span id="name-user">{{ $data->user ? $data->users->name : 'Select user first'}}</span>
                                <br><span id="privilege-user" class="{{ $data->user ? getBadge($data->users->privileges->color) : '' }}">{{ $data->user ? $data->users->privileges->name : '-' }}</span>
                            </div>
                        </div>
                        @error('user')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Branch</label>
                        <div>
                            @foreach($data_additional['branch'] as $row)
                                <input type="radio" class="btn-check" name="branch" id="option-branch-{{ $row->id }}" value="{{ $row->id }}" {{ old('branch', $data->branch) == $row->id ? 'checked' : ''}}>
                                <label class="btn btn-outline-primary rounded-5" for="option-branch-{{ $row->id }}">{{ $row->name }}</label>
                            @endforeach
                        </div>
                        @error('branch')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Coordinator</label>
                        <div>
                            @foreach($data_additional['branch_coordinator'] as $row)
                                <input type="radio" class="btn-check" name="branch_coordinator" id="option-branch-coordinator-{{ $row->id }}" value="{{ $row->id }}" {{ old('branch_coordinator', $data->branch_coordinator) == $row->id ? 'checked' : ''}}>
                                <label class="btn btn-outline-primary rounded-5" for="option-branch-coordinator-{{ $row->id }}">{{ $row->name }}</label>
                            @endforeach
                        </div>
                        @error('branch')
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
            <h5 class="text-black mb-0">Submit</h5>
            <small class="text-muted">Submit button</small>
        </div>
        <div id="submit" class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
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
