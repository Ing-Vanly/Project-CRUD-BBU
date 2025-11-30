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
            $currentUser = auth()->user();
            $userName = $currentUser->name ?? __('User');
            $nameSegments = preg_split('/\s+/', trim($userName));
            $initials = '';
            if ($nameSegments) {
                foreach ($nameSegments as $segment) {
                    if ($segment === '') {
                        continue;
                    }
                    $initials .= mb_strtoupper(mb_substr($segment, 0, 1));
                    if (mb_strlen($initials) >= 2) {
                        break;
                    }
                }
            }
            $userInitials = $initials !== '' ? $initials : 'U';
            $supportedLocales = config('app.supported_locales', []);
            $currentLocale = app()->getLocale();
            $fallbackLocaleKey = ! empty($supportedLocales) ? array_key_first($supportedLocales) : null;
            $currentLocaleMeta = $supportedLocales[$currentLocale]
                ?? ($fallbackLocaleKey ? $supportedLocales[$fallbackLocaleKey] : ['label' => strtoupper($currentLocale)]);
        @endphp
        <li class="nav-item dropdown d-flex align-items-center">
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
                    @if (!$loop->last)
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

        @if (count($supportedLocales) > 1)
            <li class="nav-item dropdown d-flex align-items-center ml-3 locale-dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown" href="#"
                    role="button" aria-haspopup="true" aria-expanded="false">
                    @if (!empty($currentLocaleMeta['flag']))
                        <img src="{{ $currentLocaleMeta['flag'] }}" alt="{{ __($currentLocaleMeta['label'] ?? $currentLocale) }}"
                            class="locale-flag mr-2">
                    @endif
                    <span class="font-weight-bold text-secondary">
                        {{ __($currentLocaleMeta['label'] ?? strtoupper($currentLocale)) }}
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right p-0 overflow-hidden">
                    @foreach ($supportedLocales as $localeCode => $localeMeta)
                        <form action="{{ route('locale.switch') }}" method="POST" class="m-0">
                            @csrf
                            <input type="hidden" name="locale" value="{{ $localeCode }}">
                            <button type="submit"
                                class="dropdown-item d-flex align-items-center {{ $localeCode === $currentLocale ? 'active' : '' }}">
                                @if (!empty($localeMeta['flag']))
                                    <img src="{{ $localeMeta['flag'] }}" alt="{{ __($localeMeta['label'] ?? $localeCode) }}"
                                        class="locale-flag mr-2">
                                @endif
                                <span>{{ __($localeMeta['label'] ?? strtoupper($localeCode)) }}</span>
                                @if ($localeCode === $currentLocale)
                                    <i class="fas fa-check text-primary ml-auto"></i>
                                @endif
                            </button>
                        </form>
                    @endforeach
                </div>
            </li>
        @endif
        
        <li class="nav-item d-flex align-items-center ml-3">
            <div class="btn-group navbar-user-dropdown">
                <button type="button" class="btn btn-light dropdown-toggle d-flex align-items-center"
                    data-toggle="dropdown">
                    <span class="navbar-profile-avatar mr-2">{{ $userInitials }}</span>
                    <span class="font-weight-bold">{{ $userName }}</span>
                    <span class="sr-only">{{ __('Toggle Dropdown') }}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <div class="dropdown-header text-center">
                        <div class="navbar-profile-avatar mb-2">{{ $userInitials }}</div>
                        <div class="font-weight-bold">{{ $userName }}</div>
                        @if ($currentUser && $currentUser->email)
                            <small class="text-muted">{{ $currentUser->email }}</small>
                        @endif
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="" class="dropdown-item">
                        <i class="fas fa-user mr-2 text-muted"></i> {{ __('My Profile') }}
                    </a>
                    <a href="{{ route('business-setting.edit') }}" class="dropdown-item">
                        <i class="fas fa-sliders-h mr-2 text-muted"></i> {{ __('System Settings') }}
                    </a>
                    <div class="dropdown-divider"></div>
                    <a id="header-logout" href="" onclick="event.preventDefault()" data-toggle="modal"
                        data-target="#modal-logout" class="dropdown-item text-danger">
                        <i class="fa fa-power-off mr-2"></i> {{ __('Logout') }}
                    </a>
                </div>
            </div>
        </li>
    </ul>
</nav>
