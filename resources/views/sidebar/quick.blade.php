@if(getUserLevel('customers') > 0)
    <a href="{{ url('customer/create') }}" class="btn btn-success w-100 text-white mb-2">@svg('heroicon-s-user', 'icon') New Customer</a>
@endif
@if(getUserLevel('warranties') > 0)
    <a href="{{ url('warranty/create') }}" class="btn btn-warning w-100 text-white mb-2">@svg('heroicon-s-credit-card', 'icon') New Warranty</a>
@endif
@if(getUserLevel('tickets') > 0)
    <a href="{{ url('ticket/create') }}" class="btn btn-primary w-100 text-white mb-2">@svg('heroicon-s-clipboard-document-list', 'icon') New Ticket</a>
@endif
@if(getUserLevel('jobs') > 0)
    <a href="{{ url('job/create') }}" class="btn btn-secondary w-100 text-white">@svg('heroicon-s-briefcase', 'icon') New Job</a>
@endif