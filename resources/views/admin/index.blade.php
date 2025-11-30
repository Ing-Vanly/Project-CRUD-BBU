@extends('admin.layouts.app')

@push('css')
<style>
.dashboard-card {
    border-radius: 1rem;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    overflow: hidden;
}

.dashboard-card .icon-wrap {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}

.chart-container {
    position: relative;
    min-height: 320px;
    height: clamp(320px, 42vh, 460px);
}

.chart-container.chart-sm {
    min-height: 240px;
    height: clamp(240px, 32vh, 360px);
}

.chart-container canvas {
    width: 100% !important;
    height: 100% !important;
}

.recent-orders-table tbody tr:hover {
    background: #f8fafc;
}
</style>
@endpush

@section('contents')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('Dashboard') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Dashboard Overview') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @php
                $statCards = [
                    [
                        'title' => __('Total Revenue'),
                        'value' => $currencySymbol . number_format($metrics['totalRevenue'], 2),
                        'icon' => 'fas fa-coins',
                        'color' => 'bg-gradient-primary',
                        'subtitle' => __('Completed orders only'),
                    ],
                    [
                        'title' => __('Orders Today'),
                        'value' => number_format($metrics['ordersToday']),
                        'icon' => 'fas fa-receipt',
                        'color' => 'bg-gradient-success',
                        'subtitle' => __('Tracked via order time'),
                    ],
                    [
                        'title' => __('Published Posts'),
                        'value' => number_format($metrics['publishedPosts']),
                        'icon' => 'fas fa-newspaper',
                        'color' => 'bg-gradient-warning',
                        'subtitle' => __('Active content pieces'),
                    ],
                    [
                        'title' => __('Active Products'),
                        'value' => number_format($metrics['activeProducts']),
                        'icon' => 'fas fa-boxes',
                        'color' => 'bg-gradient-danger',
                        'subtitle' => __('Catalog items in stock'),
                    ],
                ];
                $statusColors = [
                    'pending' => 'badge-warning',
                    'processing' => 'badge-info',
                    'completed' => 'badge-success',
                    'cancelled' => 'badge-danger',
                ];
            @endphp

            <div class="row">
                @foreach ($statCards as $card)
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card dashboard-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div>
                                        <p class="text-muted text-sm mb-1">{{ $card['title'] }}</p>
                                        <h3 class="mb-0 font-weight-bold">{{ $card['value'] }}</h3>
                                    </div>
                                    <span class="icon-wrap {{ $card['color'] }} text-white">
                                        <i class="{{ $card['icon'] }}"></i>
                                    </span>
                                </div>
                                <small class="text-muted">{{ $card['subtitle'] }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="card h-100">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="card-title mb-1">{{ __('Orders & Revenue (Last 7 Days)') }}</h3>
                                    <small class="text-muted">{{ __('Rolling performance for the previous week') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body chart-container">
                            <canvas id="ordersRevenueChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header border-0">
                            <h3 class="card-title mb-1">{{ __('Order Status Distribution') }}</h3>
                            <small class="text-muted">{{ __('Snapshot of all recorded orders') }}</small>
                        </div>
                        <div class="card-body">
                            <div class="chart-container chart-sm">
                                <canvas id="orderStatusChart"></canvas>
                            </div>
                            <ul class="list-unstyled mt-3 mb-0">
                                @foreach ($statusBreakdown as $status => $count)
                                    <li class="d-flex justify-content-between py-1 border-bottom">
                                        <span>{{ __(
                                            \Illuminate\Support\Str::headline($status)
                                        ) }}</span>
                                        <span class="font-weight-bold">{{ number_format($count) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="card h-100">
                        <div class="card-header border-0 d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">{{ __('Recent Orders') }}</h3>
                            <a href="{{ route('order.index') }}" class="text-sm text-primary">{{ __('View all orders') }}</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 recent-orders-table">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>{{ __('Order') }}</th>
                                            <th>{{ __('Customer') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Total') }}</th>
                                            <th>{{ __('Date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recentOrders as $order)
                                            @php
                                                $orderDate = $order->ordered_at ?? $order->created_at;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="font-weight-bold">{{ $order->order_number }}</span>
                                                        <small class="text-muted">{{ optional($order->product)->name ?? __('Unknown product') }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span>{{ $order->customer_name ?? __('N/A') }}</span>
                                                        <small class="text-muted">{{ $order->customer_email ?? $order->customer_phone }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    @php $badgeClass = $statusColors[$order->status] ?? 'badge-secondary'; @endphp
                                                    <span class="badge {{ $badgeClass }}">{{ __(\Illuminate\Support\Str::headline($order->status)) }}</span>
                                                </td>
                                                <td>{{ $currencySymbol }}{{ number_format($order->total_amount ?? 0, 2) }}</td>
                                                <td>{{ optional($orderDate)->format('M d, Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">{{ __('No recent orders found.') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header border-0">
                            <h3 class="card-title mb-0">{{ __('Content Highlights') }}</h3>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3">
                                    <p class="text-muted mb-1">{{ __('Latest content output') }}</p>
                                    <h4 class="mb-0">{{ number_format($metrics['publishedPosts']) }} <small class="text-muted">{{ __('Published Posts') }}</small></h4>
                                </li>
                                <li class="mb-3">
                                    <p class="text-muted mb-1">{{ __('Catalog breadth') }}</p>
                                    <h4 class="mb-0">{{ number_format($metrics['activeProducts']) }} <small class="text-muted">{{ __('Active Products') }}</small></h4>
                                </li>
                                <li>
                                    <p class="text-muted mb-1">{{ __('Orders placed today') }}</p>
                                    <h4 class="mb-0">{{ number_format($metrics['ordersToday']) }} <small class="text-muted">{{ __('Orders Today') }}</small></h4>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ordersCtx = document.getElementById('ordersRevenueChart');
            if (ordersCtx && window.Chart) {
                new Chart(ordersCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($trendLabels),
                        datasets: [
                            {
                                label: '{{ __('Orders') }}',
                                data: @json($ordersTrendData),
                                backgroundColor: 'rgba(37, 99, 235, 0.85)',
                                borderColor: 'rgba(37, 99, 235, 1)',
                                yAxisID: 'y-axis-orders',
                                barThickness: 'flex'
                            },
                            {
                                label: '{{ __('Revenue') }}',
                                data: @json($revenueTrendData),
                                borderColor: '#f59e0b',
                                backgroundColor: 'rgba(245, 158, 11, 0.15)',
                                type: 'line',
                                fill: true,
                                yAxisID: 'y-axis-revenue',
                                lineTension: 0.3,
                                pointRadius: 3
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        legend: {
                            display: true
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false
                        },
                        scales: {
                            yAxes: [
                                {
                                    id: 'y-axis-orders',
                                    position: 'left',
                                    ticks: {
                                        beginAtZero: true,
                                        callback: function(value) {
                                            return value.toFixed ? value.toFixed(0) : value;
                                        }
                                    }
                                },
                                {
                                    id: 'y-axis-revenue',
                                    position: 'right',
                                    ticks: {
                                        beginAtZero: true
                                    },
                                    gridLines: {
                                        drawOnChartArea: false
                                    }
                                }
                            ],
                            xAxes: [
                                {
                                    gridLines: {
                                        display: false
                                    }
                                }
                            ]
                        }
                    }
                });
            }

            const statusCtx = document.getElementById('orderStatusChart');
            if (statusCtx && window.Chart) {
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($statusLabels),
                        datasets: [{
                            data: @json($statusData),
                            backgroundColor: ['#f59e0b', '#0ea5e9', '#10b981', '#ef4444'],
                            borderWidth: 1,
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        legend: {
                            position: 'bottom'
                        }
                    }
                });
            }
        });
    </script>
@endpush
