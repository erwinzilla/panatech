<div class="table-footer-text">
    @if($total == 0)
        Showing {{ $total }} entries
    @else
        Showing {{ $firstItem }} to {{ $lastItem }} of {{ $total }} entries
    @endif
</div>