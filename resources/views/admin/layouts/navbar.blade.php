<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        @php
            $orderNotifications = $pendingOrderNotifications ?? ['count' => 0, 'orders' => collect()];
            $orderNotificationCount = (int) ($orderNotifications['count'] ?? 0);
            $orderNotificationItems = collect($orderNotifications['orders'] ?? []);
        @endphp
        <li class="nav-item dropdown">
            <a class="nav-link position-relative d-flex align-items-center" data-toggle="dropdown" href="#"
                role="button" aria-haspopup="true" aria-expanded="false">
                <span class="notifications-icon d-inline-flex align-items-center position-relative">
                    <i class="fas fa-bell text-secondary"></i>
                    @if ($orderNotificationCount > 0)
                        <span class="badge badge-danger position-absolute">
                            {{ $orderNotificationCount > 99 ? '99+' : $orderNotificationCount }}
                        </span>
                    @endif
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">
                    {{ $orderNotificationCount > 0 ? __(':count pending orders need review', ['count' => $orderNotificationCount]) : __('No pending orders') }}
                </span>
                <div class="dropdown-divider"></div>
                @forelse ($orderNotificationItems as $pendingOrder)
                    <a class="dropdown-item" href="{{ route('order.show', $pendingOrder) }}">
                        <div class="d-flex flex-column">
                            <span class="font-weight-bold text-sm">
                                {{ $pendingOrder->order_number }}
                            </span>
                            <span class="text-muted text-xs">
                                {{ optional($pendingOrder->product)->name ?? __('Unknown product') }}
                                &middot;
                                {{ optional($pendingOrder->ordered_at)->format('M d, Y h:i A') ?? __('Date pending') }}
                            </span>
                        </div>
                    </a>
                    @if (! $loop->last)
                        <div class="dropdown-divider"></div>
                    @endif
                @empty
                    <span class="dropdown-item text-muted small">{{ __('No pending orders to review.') }}</span>
                @endforelse
                <div class="dropdown-divider"></div>
                <a href="{{ route('order.index') }}" class="dropdown-item dropdown-footer">
                    {{ __('View all orders') }}
                </a>
            </div>
        </li>
        <li>
            <div class="margin">
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                        Super Admin
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                        <a href="" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i> {{ __('My Profile') }}
                        </a>
                        <a href="{{ route('business-setting.edit') }}" class="dropdown-item">
                            <i class="fas fa-sliders-h mr-2"></i> {{ __('System Settings') }}
                        </a>
                        <a id="header-logout" href="" onclick="event.preventDefault()" data-toggle="modal"
                            data-target="#modal-logout" class="dropdown-item">
                            <i class="fa fa-power-off mr-2"></i> {{ __('Logout') }}
                        </a>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</nav>
