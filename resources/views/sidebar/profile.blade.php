<div class="d-flex fs-6 mb-3">
    @if(Auth::user()->image)
        <img src="{{ asset('uploads/images/users/'.$row->image) }}" class="avatar rounded-circle" alt="Avatar">
    @else
        <img src="{{ asset('uploads/images/users/default.jpg') }}" class="avatar rounded-circle" alt="Avatar">
    @endif
    <div class="mx-2">
        <b class="text-black">{{ ucwords(Auth::user()->name) }}</b><br>
        <span class="badge bg-{{ Auth::user()->privileges->color }} bg-opacity-25 text-{{ Auth::user()->privileges->color }}">{{ ucwords(Auth::user()->privileges->name) }}</span>
    </div>
    <span class="ms-auto align-self-center">@svg('heroicon-s-adjustments-horizontal', 'icon')</span>
</div>