<th class="{{ isset($class) ? $class : '' }}">
    @if(!$sortable)
        <span>{{ isset($title) ? $title : 'NULL' }}</span>
    @else
        <div class="d-flex dropdown">
            <span class="align-self-center">{{ isset($title) ? $title : 'NULL' }}</span>
            <a href="#" class="btn btn-icon ms-auto dropdown-toggle no-caret shadow-none {{ $table['column'] ==  $column ? 'text-primary' : '' }}" id="dd-menu-{{ isset($column) ? $column : '' }}" data-bs-toggle="dropdown" aria-expanded="false" style="--bs-btn-hover-border-color: var(--bs-primary);--bs-btn-active-border-color: var(--bs-primary)">
                @if($table['column'] == $column)
                    @svg('heroicon-s-check-circle', 'icon-sm')
                @endif
                @svg('heroicon-s-ellipsis-vertical', 'icon-sm')
            </a>
            <ul class="dropdown-menu" aria-labelledby="dd-menu-{{ isset($column) ? $column : '' }}" data-column="{{ isset($column) ? $column : '' }}">
                <li><h6 class="dropdown-header">Sort Items</h6></li>
                <li>
                    <a class="dropdown-item {{ $table['column'] == $column && $table['sort'] == 'asc' ? 'text-primary' : '' }}" href="{{ request()->fullUrlWithQuery(['column' => $column, 'sort' => 'asc', 'target' => 'table']) }}" data-sort="asc">
                        @svg('heroicon-s-bars-arrow-down', 'icon-table icon-sm me-2')Ascending
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ $table['column'] == $column && $table['sort'] == 'desc' ? 'text-primary' : '' }}" href="{{ request()->fullUrlWithQuery(['column' => $column, 'sort' => 'desc', 'target' => 'table']) }}" data-sort="desc">
                        @svg('heroicon-s-bars-arrow-up', 'icon-table icon-sm me-2')Descending
                    </a>
                </li>
            </ul>
        </div>
    @endif
</th>