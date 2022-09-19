@if($type == 'data')
    <a href="{{ url($url.$id.'/edit') }}" class="btn btn-warning btn-icon me-2" data-bs-toggle="tooltip" data-bs-title="Edit">@svg('heroicon-s-pencil-square', 'icon-sm')</a>
    <form id="delete-{{ $id }}" method="post" action="{{ url($url.$id) }}" onsubmit="confirm_delete({{ $id }});return false;" data-bs-toggle="tooltip" data-bs-title="Delete">
        @method('delete')
        @csrf
        <button type="submit" class="btn btn-danger btn-icon">@svg('heroicon-s-trash', 'icon-sm')</button>
    </form>
@endif
@if($type == 'trash')
    <a href="{{ url($url.'restore/'.$id) }}" class="btn btn-outline-info btn-icon me-2 restore-action-link" data-bs-toggle="tooltip" data-bs-title="Restore">
        @svg('heroicon-s-arrow-up-on-square', 'icon')
    </a>
    <a id="delete-link-{{ $id }}" href="{{ url($url.'delete/'.$id) }}" class="btn btn-outline-danger btn-icon delete-action-link" onclick="confirm_delete_link({{ $id }}); return false;" data-bs-toggle="tooltip" data-bs-title="Delete Permanent">
        @svg('heroicon-s-trash', 'icon')
    </a>
@endif
