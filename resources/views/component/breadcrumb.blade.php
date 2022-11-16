{{--    Breadcrumb--}}
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">@svg('heroicon-s-command-line', 'icon')</li>
        @if(Request::segment(1))
            <li class="breadcrumb-item"><a href="{{ url(Request::segment(1)) }}" class="text-decoration-none">{{ ucwords(Request::segment(1)) }}</a></li>
        @else
            <li class="breadcrumb-item"><a href="{{ url('home') }}" class="text-decoration-none">Home</a></li>
        @endif
        @if(Request::segment(2))
            @if(!is_numeric(Request::segment(2)))
                <li class="breadcrumb-item"><a href="{{ url(Request::segment(1).'/'.Request::segment(2)) }}" class="text-decoration-none">{{ ucwords(Request::segment(2)) }}</a></li>
            @endif
        @endif
        @if(Request::segment(3))
{{--            Filter ID for edit data--}}
            @if(!is_numeric(Request::segment(3)))
                <li class="breadcrumb-item"><a href="{{ url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3)) }}" class="text-decoration-none">{{ ucwords(Request::segment(3)) }}</a></li>
            @endif
        @endif
        @if(Request::segment(4))
            @if(!is_numeric(Request::segment(4)))
                <li class="breadcrumb-item"><a href="{{ url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/'.Request::segment(4)) }}" class="text-decoration-none">{{ ucwords(Request::segment(4)) }}</a></li>
            @endif
        @endif
        @if(Request::segment(5))
            @if(!is_numeric(Request::segment(5)))
                <li class="breadcrumb-item"><a href="{{ url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/'.Request::segment(4).'/'.Request::segment(5)) }}" class="text-decoration-none">{{ ucwords(Request::segment(5)) }}</a></li>
            @endif
        @endif
        @if(Request::segment(6))
            @if(!is_numeric(Request::segment(6)))
                <li class="breadcrumb-item"><a href="{{ url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/'.Request::segment(4).'/'.Request::segment(5).'/'.Request::segment(6)) }}" class="text-decoration-none">{{ ucwords(Request::segment(6)) }}</a></li>
            @endif
        @endif
    </ol>
</nav>
<h1 class="text-default fw-bold">{{ ucwords($title) }}</h1>