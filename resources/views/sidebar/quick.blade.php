@if(getUserLevel('customers') > 0)
    <a href="{{ url('customer') }}" class="btn btn-success w-100 text-white mb-2">@svg('heroicon-s-user', 'icon') Customer</a>
@endif
@if(getUserLevel('warranties') > 0)
    <a href="{{ url('warranty') }}" class="btn btn-warning w-100 text-white mb-2">@svg('heroicon-s-credit-card', 'icon') Warranty</a>
@endif
@if(getUserLevel('tickets') > 0)
    <a href="{{ url('ticket') }}" class="btn btn-primary w-100 text-white mb-2">@svg('heroicon-s-clipboard-document-list', 'icon') Ticket</a>
@endif
@if(getUserLevel('jobs') > 0)
    <a href="{{ url('job') }}" class="btn btn-secondary w-100 text-white mb-2">@svg('heroicon-s-briefcase', 'icon') Job</a>
@endif
@if(getUserLevel('branches') > 0)
    <a href="{{ url('branch/service/sabbr') }}" class="btn btn-danger w-100 text-white">@svg('heroicon-s-chart-bar', 'icon') SABBR</a>
@endif