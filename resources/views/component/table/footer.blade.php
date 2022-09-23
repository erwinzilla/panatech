<div class="table-footer">
{{--    @include('component.table.footer.text', ['total' => $total, 'firstItem' => $firstItem, 'lastItem' => $lastItem])--}}
{{--    @include('component.table.footer.pagination', ['data' => $data])--}}
    {{ $data->withQueryString()->appends(['target' => 'table'])->links() }}
</div>