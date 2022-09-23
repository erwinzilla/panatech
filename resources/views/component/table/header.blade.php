<div class="table-header">
{{--    <form method="get" action="{{ url()->full() }}" class="d-flex w-100">--}}
    @include('component.table.header.select', ['perPage' => $perPage])
    @include('component.table.header.search', ['search' => $search])
{{--    </form>--}}
</div>