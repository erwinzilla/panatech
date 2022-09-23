<div class="table-select">
    <select name="perPage" class="form-select" aria-label="Per Page Selector">
        <option value="5" {{ $perPage == 5 ? 'selected' : ''}}>5</option>
        <option value="10" {{ $perPage == 10 ? 'selected' : ''}}>10</option>
        <option value="15" {{ $perPage == 15 ? 'selected' : ''}}>15</option>
        <option value="20" {{ $perPage == 20 ? 'selected' : ''}}>20</option>
        <option value="25" {{ $perPage == 25 ? 'selected' : ''}}>25</option>
    </select>
    <span class="align-self-center">entries per page</span>
</div>