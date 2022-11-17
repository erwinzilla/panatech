@extends('layout.template')

@section('title', 'Home')

@section('sidebar')
    @include($config['blade'].'.sidebar')
@endsection

@section('content')
    <div class="d-flex justify-content-between">
        <div>
            @include('component.breadcrumb')
        </div>
        <div>
            <span class="{{ getBadge('primary') }} me-2">Day: {{ getWorkingDay() - getWorkingDay(date('Y-m-d')) }}</span>
            <span class="text-default align-self-center">@svg('heroicon-s-calendar', 'icon me-1')<span style="vertical-align: -2px;">{{ date('D, d M Y') }}</span></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card bg-gradient-primary">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col-auto ms-3">
                            @svg('heroicon-s-sparkles', 'icon-lg')
                        </div>
                        <div class="col">
                            <span>Result This Month</span>
                            @php
                                $sabbr = ($data['sabbr']->rates * ($data['target']->sabbr_div / 100));
                                $speed_repair = ((($user->speed_repair / $user->set_repair * 100) / $data['target']->speed_repair_target) * $data['target']->speed_repair_div / 100);
                                $income = (($user->income / (getWorkingDay() * $data['target']->income_target) * 100) * ($data['target']->income_div / 100) / 100);
                                $total = $sabbr + $speed_repair + $income;
                            @endphp
                            <h3 class="fw-bold mb-0">{!! getPrice($total * $data['target']->incentive) !!}</h3>
                            <small>Total: {{ number_format($total, 2, ',','.') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-gradient-secondary">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <span>Set Repair</span>
                            <h2 class="mb-0">{{ $user->set_repair }}</h2>
                            <a href="{{ url('job/create') }}" class="text-decoration-none text-white small">
                                @svg('heroicon-s-arrow-right-circle', 'icon-sm') Add more job
                            </a>
                        </div>
                        <div class="col-auto me-3">
                            @svg('heroicon-s-briefcase', 'icon-lg')
                        </div>
                    </div>
                </div>
            </div>
            <table class="table w-100">
                <tr>
                    <td class="text-muted">Target SABBR</td>
                    <td class="text-end">{{ $data['target'] ? $data['target']->sabbr_target.'%' : '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Target Rates</td>
                    <td class="text-end">{{ $data['sabbr'] ? number_format($data['sabbr']->rates, 2, ',', '.') : '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Div</td>
                    <td class="text-end">{{ $data['target'] ? $data['target']->sabbr_div.'%' : '-' }}</td>
                </tr>
                <tr>
                    <td class="text-primary">Result</td>
                    <td class="text-end">{{ $data['sabbr'] ? number_format($data['sabbr']->rates * ($data['target']->sabbr_div / 100), 2, ',', '.') : '-' }} <span class="text-muted">/ {{ $data['target'] ? $data['target']->sabbr_div / 100 : '-' }}</span></td>
                </tr>
            </table>
        </div>
        <div class="col-md-4">
            <div class="card bg-gradient-warning">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <span>Speed Repair</span>
                            <h2 class="mb-0">{{ number_format($user->speed_repair / $user->set_repair * 100, 2, ',','.') }}%</h2>
                            <a href="{{ url('job/create') }}" class="text-decoration-none text-white small">
                                @svg('heroicon-s-arrow-right-circle', 'icon-sm') Add more job
                            </a>
                        </div>
                        <div class="col-auto me-3">
                            @svg('heroicon-s-clock', 'icon-lg')
                        </div>
                    </div>
                </div>
            </div>
            <table class="table w-100">
                <tr>
                    <td class="text-muted">Target</td>
                    <td class="text-end">{{ $data['target'] ? $data['target']->speed_repair_target.'%' : '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Target Rates</td>
                    <td class="text-end">{{ $data['target'] ? number_format(($user->speed_repair / $user->set_repair * 100) / $data['target']->speed_repair_target, 2, ',', '.') : '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Div</td>
                    <td class="text-end">{{ $data['target'] ? $data['target']->speed_repair_div.'%' : '-' }}</td>
                </tr>
                <tr>
                    <td class="text-secondary">Result</td>
                    <td class="text-end">{{ $data['target'] ? number_format((($user->speed_repair / $user->set_repair * 100) / $data['target']->speed_repair_target) * $data['target']->speed_repair_div / 100, 2, ',', '.') : '-' }} <span class="text-muted">/ {{ $data['target']->speed_repair_div / 100 }}</span></td>
                </tr>
            </table>
        </div>
        <div class="col-md-4">
            <div class="card bg-gradient-danger">
                <div class="card-body text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <span>Total Income</span>
                            <h3 class="mb-0">{!! getPrice($user->income) !!}</h3>
                            <a href="{{ url('job/create') }}" class="text-decoration-none text-white small">
                                @svg('heroicon-s-arrow-right-circle', 'icon-sm') Add more job
                            </a>
                        </div>
                        <div class="col-auto me-3">
                            @svg('heroicon-s-banknotes', 'icon-lg')
                        </div>
                    </div>
                </div>
            </div>
            <table class="table w-100">
                <tr>
                    <td class="text-muted">Target</td>
                    <td class="text-end">{!! $data['target'] ? getPrice(getWorkingDay() * $data['target']->income_target, 'justify-content-end') : '-' !!}</td>
                </tr>
                <tr>
                    <td class="text-muted">Target Rates</td>
                    <td class="text-end">{{ $data['target'] ? number_format($user->income / (getWorkingDay() * $data['target']->income_target), 2, ',', '.') : '-'}}</td>
                </tr>
                <tr>
                    <td class="text-muted">Div</td>
                    <td class="text-end">{{ $data['target'] ? $data['target']->income_div.'%' : '-' }}</td>
                </tr>
                <tr>
                    <td class="text-warning">Result</td>
                    <td class="text-end">{{ $data['target'] ? number_format(($user->income / (getWorkingDay() * $data['target']->income_target) * 100) * ($data['target']->income_div / 100) / 100, 2, ',', '.') : '-' }} <span class="text-muted">/ {{ $data['target'] ? $data['target']->income_div / 100 : '-'}}</span></td>
                </tr>
            </table>
        </div>
    </div>
@endsection
