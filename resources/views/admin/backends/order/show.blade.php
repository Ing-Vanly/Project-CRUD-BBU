@extends('admin.layouts.app')

@section('contents')
    <section class="content-header">
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center flex-wrap">
                                        <h3 class="card-title mb-0">{{ $order->order_number }}</h3>
                                        <span
                                            class="badge badge-{{ match ($order->status) {
                                                'completed' => 'success',
                                                'processing' => 'primary',
                                                'cancelled' => 'danger',
                                                default => 'warning',
                                            } }} ml-2 text-uppercase">{{ $order->status }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <div class="d-flex justify-content-md-end flex-wrap">
                                        <a href="{{ route('order.index') }}"
                                            class="btn btn-outline-secondary btn-sm mr-2 mb-2 mb-md-0">
                                            <i class="fas fa-arrow-left mr-1"></i> {{ __('Back to list') }}
                                        </a>
                                        <a href="{{ route('order.edit', $order) }}"
                                            class="btn btn-primary btn-sm mb-2 mb-md-0">
                                            <i class="fas fa-edit mr-1"></i> {{ __('Edit') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h5 class="text-muted text-uppercase">{{ __('Customer Information') }}</h5>
                                    <dl class="row">
                                        <dt class="col-sm-4">{{ __('Name') }}</dt>
                                        <dd class="col-sm-8">{{ $order->customer_name }}</dd>

                                        <dt class="col-sm-4">{{ __('Email') }}</dt>
                                        <dd class="col-sm-8">{{ $order->customer_email ?? __('Not provided') }}</dd>

                                        <dt class="col-sm-4">{{ __('Phone') }}</dt>
                                        <dd class="col-sm-8">{{ $order->customer_phone ?? __('Not provided') }}</dd>
                                    </dl>
                                </div>
                                <div class="col-lg-6">
                                    <h5 class="text-muted text-uppercase">{{ __('Order Information') }}</h5>
                                    <dl class="row">
                                        <dt class="col-sm-4">{{ __('Product') }}</dt>
                                        <dd class="col-sm-8">{{ optional($order->product)->name ?? __('N/A') }}</dd>

                                        <dt class="col-sm-4">{{ __('Quantity') }}</dt>
                                        <dd class="col-sm-8">{{ $order->quantity }}</dd>

                                        <dt class="col-sm-4">{{ __('Order Date') }}</dt>
                                        <dd class="col-sm-8">{{ $order->ordered_at->format('M d, Y h:i A') }}</dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-text text-center">{{ __('Unit Price') }}</span>
                                            <span
                                                class="info-box-number text-center mb-0">${{ number_format($order->unit_price, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-text text-center">{{ __('Quantity') }}</span>
                                            <span class="info-box-number text-center mb-0">{{ $order->quantity }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-text text-center">{{ __('Total Amount') }}</span>
                                            <span
                                                class="info-box-number text-center mb-0">${{ number_format($order->total_amount, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($order->notes)
                                <div class="mt-4">
                                    <h5 class="text-muted text-uppercase">{{ __('Notes') }}</h5>
                                    <p class="mb-0">{{ $order->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
