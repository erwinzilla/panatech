@include('form.header.start')
{{--    Body--}}
<div class="row mt-3">
    <div class="col-md-3">
        <h5 class="text-black mb-0">Search Part</h5>
        <small class="text-muted">Cari part berdasarkan data</small>
    </div>
    <div id="main" class="col-md-9">
        <div class="card mb-3">
            <div class="card-body">
                <input type="hidden" name="job_id" value="{{ old('id', $data->id) }}">
                <div class="d-flex justify-content-between w-100">
                    <button id="find-part" type="button" class="btn btn-primary">@svg('heroicon-s-magnifying-glass', 'icon-sm me-2') Cari Part</button>
                    <button id="submit-part" type="button" class="btn btn-success">@svg('heroicon-s-paper-airplane', 'icon-sm me-2') Submit Parts</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body p-0">
                <div id="table-data-part">
                    <div class="table-container table-responsive">
                        <table class="table table-striped mb-0 data-table align-middle">
                            <thead>
                            <tr>
                                @include('component.table.title', ['title' => '#', 'column' => 'id', 'sortable' => false, 'class' => 'text-center'])
                                @include('component.table.title', ['title' => 'Part No.', 'column' => 'sku', 'sortable' => false])
                                @include('component.table.title', ['title' => 'Description', 'column' => 'name', 'sortable' => false])
                                @include('component.table.title', ['title' => 'Price', 'column' => 'price', 'sortable' => false])
                                @include('component.table.title', ['title' => 'Qty', 'column' => 'qty', 'sortable' => false])
                                @include('component.table.title', ['title' => 'Total', 'column' => 'total', 'sortable' => false])
                                @include('component.table.title', ['title' => 'Action', 'column' => 'action', 'sortable' => false])
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7" class="text-muted text-center">Silahkan Masukan Part</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('form.header.end')