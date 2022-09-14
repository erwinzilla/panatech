@extends('template')

@section('title', ucwords($type).' User Privilege')

@section('sidebar')
    <div class="bg-white">
        @include('sidebar-profile')
        <ul class="nav nav-pills flex-column" id="sidebar">
            <li class="nav-item mb-1">
                <a href="{{ url('user/privilege') }}" class="nav-link" aria-current="page">@svg('heroicon-o-credit-card', 'icon') Privilege</a>
            </li>
            <li class="nav-item mb-1">
                <a href="#main" class="nav-link">@svg('heroicon-o-plus', 'icon') Create new</a>
            </li>
            <li class="nav-item">
                <a href="{{ url('user/privilege/trash') }}" class="nav-link text-danger">@svg('heroicon-o-trash', 'icon') Trash</a>
            </li>
        </ul>
        <hr class="border-dashed">
        <a href="#" class="btn btn-primary w-100 text-white">@svg('heroicon-s-clipboard-document-list', 'icon') New Ticket</a>
    </div>
@endsection

@section('content')
    {{--    Breadcrumb--}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">@svg('heroicon-s-command-line', 'icon')</li>
            <li class="breadcrumb-item">User</li>
            <li class="breadcrumb-item">Privilege</li>
            <li class="breadcrumb-item active" aria-current="page">{{ ucwords($type) }}</li>
        </ol>
    </nav>
    <h1 class="fw-bold">{{ ucwords($type) }} User Privilege</h1>

    {{--    Body--}}
    <div class="row mt-3" data-bs-spy="scroll" data-bs-target="#sidebar" data-bs-smooth-scroll="true">
        <div class="col-md-3">
            <h5 class="text-black mb-0">Main Menu</h5>
            <small class="text-muted">Data ini yang nanti akan digunakan saat memberikan akses kepada pengguna</small>
        </div>
        <div id="main" class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
                    @if($type == 'create')
                        <form action="{{ url('user/privilege') }}" method="post">
                    @endif
                    @if($type == 'edit')
                        <form action="{{ url('user/privilege/'.$data->id) }}" method="post">
                            @method('put')
                    @endif
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control w-50 @error('name') is-invalid @enderror" value="{{ old('name', $data->name) }}" placeholder="Nama Hak Akses / Privilege">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <hr class="border-dashed">
                            <div class="mb-3">
                                <label class="form-label">Access Tickets</label>
                                <div>
                                    <input type="radio" class="btn-check" name="tickets" id="option-tickets-0" value="0" {{ old('tickets', $data->tickets) == 0 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-danger rounded-5" for="option-tickets-0">@svg('heroicon-o-x-circle', 'icon-sm') Forbidden</label>

                                    <input type="radio" class="btn-check" name="tickets" id="option-tickets-1" value="1" autocomplete="off" {{ old('tickets', $data->tickets) == 1 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-primary rounded-5" for="option-tickets-1">@svg('heroicon-o-eye', 'icon-sm') Only See</label>

                                    <input type="radio" class="btn-check" name="tickets" id="option-tickets-2" value="2" autocomplete="off" {{ old('tickets', $data->tickets) == 2 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-primary rounded-5" for="option-tickets-2">@svg('heroicon-o-pencil-square', 'icon-sm') Can CRUD</label>

                                    <input type="radio" class="btn-check" name="tickets" id="option-tickets-3" value="3" autocomplete="off" {{ old('tickets', $data->tickets) == 3 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-success rounded-5" for="option-tickets-3">@svg('heroicon-o-sparkles', 'icon-sm') All Access</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Access Customers</label>
                                <div>
                                    <input type="radio" class="btn-check" name="customers" id="option-customers-0" value="0" {{ old('customers', $data->customers) == 0 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-danger rounded-5" for="option-customers-0">@svg('heroicon-o-x-circle', 'icon-sm') Forbidden</label>

                                    <input type="radio" class="btn-check" name="customers" id="option-customers-1" value="1" autocomplete="off" {{ old('customers', $data->customers) == 1 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-primary rounded-5" for="option-customers-1">@svg('heroicon-o-eye', 'icon-sm') Only See</label>

                                    <input type="radio" class="btn-check" name="customers" id="option-customers-2" value="2" autocomplete="off" {{ old('customers', $data->customers) == 2 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-primary rounded-5" for="option-customers-2">@svg('heroicon-o-pencil-square', 'icon-sm') Can CRUD</label>

                                    <input type="radio" class="btn-check" name="customers" id="option-customers-3" value="3" autocomplete="off" {{ old('customers', $data->customers) == 3 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-success rounded-5" for="option-customers-3">@svg('heroicon-o-sparkles', 'icon-sm') All Access</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Access Products</label>
                                <div>
                                    <input type="radio" class="btn-check" name="products" id="option-products-0" value="0" {{ old('products', $data->products) == 0 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-danger rounded-5" for="option-products-0">@svg('heroicon-o-x-circle', 'icon-sm') Forbidden</label>

                                    <input type="radio" class="btn-check" name="products" id="option-products-1" value="1" autocomplete="off" {{ old('products', $data->products) == 1 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-primary rounded-5" for="option-products-1">@svg('heroicon-o-eye', 'icon-sm') Only See</label>

                                    <input type="radio" class="btn-check" name="products" id="option-products-2" value="2" autocomplete="off" {{ old('products', $data->products) == 2 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-primary rounded-5" for="option-products-2">@svg('heroicon-o-pencil-square', 'icon-sm') Can CRUD</label>

                                    <input type="radio" class="btn-check" name="products" id="option-products-3" value="3" autocomplete="off" {{ old('products', $data->products) == 3 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-success rounded-5" for="option-products-3">@svg('heroicon-o-sparkles', 'icon-sm') All Access</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Access Reports</label>
                                <div>
                                    <input type="radio" class="btn-check" name="reports" id="option-reports-0" value="0" {{ old('reports', $data->reports) == 0 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-danger rounded-5" for="option-reports-0">@svg('heroicon-o-x-circle', 'icon-sm') Forbidden</label>

                                    <input type="radio" class="btn-check" name="reports" id="option-reports-1" value="1" autocomplete="off" {{ old('reports', $data->reports) == 1 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-primary rounded-5" for="option-reports-1">@svg('heroicon-o-eye', 'icon-sm') Only See</label>

                                    <input type="radio" class="btn-check" name="reports" id="option-reports-2" value="2" autocomplete="off" {{ old('reports', $data->reports) == 2 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-primary rounded-5" for="option-reports-2">@svg('heroicon-o-pencil-square', 'icon-sm') Can CRUD</label>

                                    <input type="radio" class="btn-check" name="reports" id="option-reports-3" value="3" autocomplete="off" {{ old('reports', $data->reports) == 3 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-success rounded-5" for="option-reports-3">@svg('heroicon-o-sparkles', 'icon-sm') All Access</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Access Users</label>
                                <div>
                                    <input type="radio" class="btn-check" name="users" id="option-users-0" value="0" {{ old('users', $data->users) == 0 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-danger rounded-5" for="option-users-0">@svg('heroicon-o-x-circle', 'icon-sm') Forbidden</label>

                                    <input type="radio" class="btn-check" name="users" id="option-users-1" value="1" autocomplete="off" {{ old('users', $data->users) == 1 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-primary rounded-5" for="option-users-1">@svg('heroicon-o-eye', 'icon-sm') Only See</label>

                                    <input type="radio" class="btn-check" name="users" id="option-users-2" value="2" autocomplete="off" {{ old('users', $data->users) == 2 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-primary rounded-5" for="option-users-2">@svg('heroicon-o-pencil-square', 'icon-sm') Can CRUD</label>

                                    <input type="radio" class="btn-check" name="users" id="option-users-3" value="3" autocomplete="off" {{ old('users', $data->users) == 3 ? 'checked' : ''}}>
                                    <label class="btn btn-outline-success rounded-5" for="option-users-3">@svg('heroicon-o-sparkles', 'icon-sm') All Access</label>
                                </div>
                            </div>
                            <hr class="border-dashed">
                            <div class="mb-3">
                                <label class="form-label">Warna Label</label>
                                <div>
                                    <input type="radio" class="btn-check" name="color" id="option-color-primary" value="primary" autocomplete="off" {{ old('color', $data->color) == 'primary' ? 'checked' : ''}}>
                                    <label class="btn btn-outline-primary rounded-5" for="option-color-primary">Primary</label>

                                    <input type="radio" class="btn-check" name="color" id="option-color-purple" value="purple" autocomplete="off" {{ old('color', $data->color) == 'purple' ? 'checked' : ''}}>
                                    <label class="btn btn-outline-purple rounded-5" for="option-color-purple">Purple</label>

                                    <input type="radio" class="btn-check" name="color" id="option-color-teal" value="teal" autocomplete="off" {{ old('color', $data->color) == 'teal' ? 'checked' : ''}}>
                                    <label class="btn btn-outline-teal rounded-5" for="option-color-teal">Teal</label>

                                    <input type="radio" class="btn-check" name="color" id="option-color-blue" value="blue" autocomplete="off" {{ old('color', $data->color) == 'blue' ? 'checked' : ''}}>
                                    <label class="btn btn-outline-blue rounded-5" for="option-color-blue">Blue</label>

                                    <input type="radio" class="btn-check" name="color" id="option-color-orange" value="orange" autocomplete="off" {{ old('color', $data->color) == 'orange' ? 'checked' : ''}}>
                                    <label class="btn btn-outline-orange rounded-5" for="option-color-orange">Orange</label>

                                    <input type="radio" class="btn-check" name="color" id="option-color-yellow" value="yellow" autocomplete="off" {{ old('color', $data->color) == 'yellow' ? 'checked' : ''}}>
                                    <label class="btn btn-outline-yellow rounded-5" for="option-color-yellow">Yellow</label>

                                    <input type="radio" class="btn-check" name="color" id="option-color-green" value="green" autocomplete="off" {{ old('color', $data->color) == 'green' ? 'checked' : ''}}>
                                    <label class="btn btn-outline-green rounded-5" for="option-color-green">Green</label>

                                    <input type="radio" class="btn-check" name="color" id="option-color-red" value="red" autocomplete="off" {{ old('color', $data->color) == 'red' ? 'checked' : ''}}>
                                    <label class="btn btn-outline-red rounded-5" for="option-color-red">Red</label>
                                </div>
                            </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <button type="reset" class="btn btn-outline-primary me-2">Reset</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
