<div class="d-flex fs-6 mb-3">
    @if(Auth::user()->image)
        <img src="{{ asset('uploads/images/users/'.Auth::user()->image) }}" class="avatar rounded-circle" alt="Avatar">
    @else
        <img src="{{ asset('uploads/images/users/default.jpg') }}" class="avatar rounded-circle" alt="Avatar">
    @endif
    <div class="mx-2">
        <b class="text-black">{{ ucwords(Auth::user()->name) }}</b><br>
        <span class="badge bg-{{ Auth::user()->privileges->color }} bg-opacity-25 text-{{ Auth::user()->privileges->color }}">{{ ucwords(Auth::user()->privileges->name) }}</span>
    </div>
    <div class="dropdown ms-auto align-self-center">
        <button class="btn dropdown-toggle no-caret btn-icon" type="button" data-bs-toggle="dropdown" aria-expanded="false">@svg('heroicon-s-adjustments-horizontal', 'icon')</button>
        <ul class="dropdown-menu">
            <li>
                <a href="{{ url('user/'.Auth::user()->username) }}" class="dropdown-item">@svg('heroicon-s-cog', 'icon-sm me-1') Edit Profile</a>
            </li>
        </ul>
    </div>
</div>