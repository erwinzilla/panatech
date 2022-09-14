<div class="d-flex fs-6 mb-3">
    <img src="https://erwinzilla.com/v2/uploads/images/users/1642948549.jpg" class="avatar rounded-circle" alt="Avatar">
    <div class="mx-2">
        <b class="text-black">{{ ucwords(Auth::user()->name) }}</b><br>
        <span class="badge bg-{{ Auth::user()->privileges->color }} bg-opacity-25 text-{{ Auth::user()->privileges->color }}">{{ ucwords(Auth::user()->privileges->name) }}</span>
    </div>
    <span class="ms-auto align-self-center">@svg('heroicon-s-adjustments-horizontal', 'icon')</span>
</div>