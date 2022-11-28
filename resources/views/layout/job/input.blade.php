@extends('layout.template')

@section('title', $type == 'edit' ? $data->customer_name.' :: '.$data->name : 'Create Job Desk')

@section('sidebar')
    @include($config['blade'].'.sidebar')
@endsection

@section('content')
    @include('component.breadcrumb')

    <ul class="nav nav-pills" id="tabJob" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="main-tab" data-bs-toggle="tab" data-bs-target="#main-tab-pane" type="button" role="tab" aria-controls="main-tab-pane" aria-selected="true">Main Menu</button>
        </li>
        @if($type == 'edit')
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="part-tab" data-bs-toggle="tab" data-bs-target="#part-tab-pane" type="button" role="tab" aria-controls="part-tab-pane" aria-selected="false">Spare Part</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="invoice-tab" data-bs-toggle="tab" data-bs-target="#invoice-tab-pane" type="button" role="tab" aria-controls="invoice-tab-pane" aria-selected="false">Invoice</button>
            </li>
        @endif
    </ul>
    <div class="tab-content" id="tabJobContent">
        <div class="tab-pane fade show active" id="main-tab-pane" role="tabpanel" aria-labelledby="main-tab" tabindex="0">
            @include($config['blade'].'.input.main')
        </div>
        @if($type == 'edit')
            <div class="tab-pane fade" id="part-tab-pane" role="tabpanel" aria-labelledby="part-tab" tabindex="0">
                @include($config['blade'].'.input.part')
            </div>
            <div class="tab-pane fade" id="invoice-tab-pane" role="tabpanel" aria-labelledby="invoice-tab" tabindex="0">
                @include($config['blade'].'.input.invoice')
            </div>
        @endif
    </div>

    @include('component.modal.table')
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('assets/js/layout/job/input.js') }}"></script>
@endsection
