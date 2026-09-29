@extends('layouts.admin')
@section('page-title')
    {{ __('Dashboard') }}
@endsection

{{-- @section('breadcrumb ')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
@endsection --}}

@section('content')
<div class="row">
    <div class="col-sm-12">

        <div class="row">

            <div class="col-lg-4 col-md-6 col-12">
                <a href="{{ route('user.index')}}" class="text-decoration-none">
                    <div class="card dashboard-stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-wrapper stat-icon-primary">
                                        <i class="ti ti-users"></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="stat-label-caption">{{ __('Total Users') }} : {{$user->total_user}}</div>
                                        <h6 class="stat-title">{{ __('Paid Users') }}</h6>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h4 class="stat-number text-primary">{{$user['total_paid_user'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <a href="{{ route('order.index')}}" class="text-decoration-none">
                    <div class="card dashboard-stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-wrapper stat-icon-info">
                                        <i class="ti ti-shopping-cart"></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="stat-label-caption">{{ __('Total Orders') }}: {{ $user->total_orders}}</div>
                                        <h6 class="stat-title">{{ __('Total Order Amount') }}</h6>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h4 class="stat-number text-info">{{ (!empty(env('CURRENCY_SYMBOL')) ? env('CURRENCY_SYMBOL') : '$') . $user['total_orders_price'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <a href="{{ route('plans.index')}}" class="text-decoration-none">
                    <div class="card dashboard-stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-wrapper stat-icon-warning">
                                        <i class="ti ti-trophy"></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="stat-label-caption">{{ __('Total Plan') }}: {{$user['total_plan']}}</div>
                                        <h6 class="stat-title">{{ __('Most Purchase Plan') }}</h6>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h4 class="stat-number text-warning">{{ $user['most_purchese_plan'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>
   </div>

    @include('dashboard.partials.login_analytics')

    <div class="col-sm-12">
        <div class="card dashboard-table-card">
            <div class="card-header">
                <h5><i class="ti ti-chart-area-line text-primary"></i> {{ __('Recent Order') }}</h5>
            </div>
            <div class="card-body">
                <div id="chart-sales" height="300" class="p-3"></div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('script-page')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script>
         (function () {
        var chartBarOptions = {
            series: [
                {
                    name: '{{__("Order")}}',
                    data:  {!! json_encode($chartData['data']) !!},

                },
            ],

            chart: {
                height: 300,
                type: 'area',
                // type: 'line',
                dropShadow: {
                    enabled: true,
                    color: '#000',
                    top: 18,
                    left: 7,
                    blur: 10,
                    opacity: 0.2
                },
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            title: {
                text: '',
                align: 'left'
            },
            xaxis: {
                categories: {!! json_encode($chartData['label']) !!},
                title: {
                    text: ''
                }
            },
            colors: ['#6fd944', '#6fd944'],

            grid: {
                strokeDashArray: 4,
            },
            legend: {
                show: false,
            },
            // markers: {
            //     size: 4,
            //     colors: ['#ffa21d', '#FF3A6E'],
            //     opacity: 0.9,
            //     strokeWidth: 2,
            //     hover: {
            //         size: 7,
            //     }
            // },
            yaxis: {
                title: {
                    text: ''
                },

            }

        };
        var arChart = new ApexCharts(document.querySelector("#chart-sales"), chartBarOptions);
        arChart.render();
        })();
    </script>
@endpush
