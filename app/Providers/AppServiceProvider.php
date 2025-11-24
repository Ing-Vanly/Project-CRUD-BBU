<?php

namespace App\Providers;

use App\Models\BusinessSetting;
use App\Models\Order;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            static $businessSetting;
            static $resolved = false;

            if (!$resolved) {
                $resolved = true;
                $businessSetting = Schema::hasTable('business_settings')
                    ? BusinessSetting::first()
                    : null;
            }

            $view->with('appBusinessSetting', $businessSetting);
        });

        View::composer('admin.layouts.navbar', function ($view) {
            static $notificationData;
            static $notificationsResolved = false;

            if (!$notificationsResolved) {
                $notificationsResolved = true;
                $notificationData = [
                    'count' => 0,
                    'orders' => collect(),
                ];

                if (Schema::hasTable('orders')) {
                    $notificationData['count'] = Order::where('status', 'pending')->count();
                    $notificationData['orders'] = Order::with('product')
                        ->where('status', 'pending')
                        ->latest('ordered_at')
                        ->take(5)
                        ->get();
                }
            }

            $view->with('pendingOrderNotifications', $notificationData);
        });
    }
}
