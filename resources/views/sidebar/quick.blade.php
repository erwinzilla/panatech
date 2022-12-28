<div class="row g-1">
    @if(getUserLevel('customers') > 0)
        <div class="col-6">
            <a href="{{ url('customer') }}" class="btn btn-success text-white w-100 h-100">@svg('heroicon-s-user', 'icon') Customer</a>
        </div>
    @endif
    @if(getUserLevel('warranties') > 0)
        <div class="col-6">
            <a href="{{ url('warranty') }}" class="btn btn-warning text-white w-100 h-100">@svg('heroicon-s-credit-card', 'icon') Warranty</a>
        </div>
    @endif
    @if(getUserLevel('tickets') > 0)
        <div class="col-6">
            <a href="{{ url('ticket') }}" class="btn btn-primary text-white w-100 h-100">@svg('heroicon-s-clipboard-document-list', 'icon') Ticket</a>
        </div>
    @endif
    @if(getUserLevel('jobs') > 0)
        <div class="col-6">
            <a href="{{ url('job') }}" class="btn btn-secondary text-white w-100 h-100">@svg('heroicon-s-briefcase', 'icon') Job</a>
        </div>
    @endif
    @if(getUserLevel('branches') > 0)
        <div class="col-6">
            <a href="{{ url('branch/service/sabbr') }}" class="btn btn-danger text-white w-100 h-100">@svg('heroicon-s-chart-bar', 'icon') SABBR</a>
        </div>
    @endif
</div>