@extends('layout.template')

@section('title', ucwords($title))

@section('sidebar')
    @include('layout.branch.sidebar')
@endsection

@section('content')
    @include('component.breadcrumb')
    <h1 class="fw-bold">{{ ucwords($title) }}</h1>

    {{--    Body--}}
    <div class="row mt-3">
        <div class="col-md-3">
            <h5 class="text-black mb-0">Main Menu</h5>
            <small class="text-muted">Data ini yang nanti akan digunakan saat memberikan nama cabang</small>
        </div>
        <div id="main" class="col-md-9">
            @if($type == 'create')
                <form action="{{ url('branch') }}" method="post">
            @endif
            @if($type == 'edit')
                <form action="{{ url('branch/'.$data->id) }}" method="post">
                @method('put')
            @endif
            @csrf
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
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        @include('form.button.submit')
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection
