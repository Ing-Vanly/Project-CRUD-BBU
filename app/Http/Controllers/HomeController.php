<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $this->enforcePermission('dashboard.view');

        $currencySymbol = config('app.currency_symbol', '$');

        $metrics = [
            'totalRevenue' => (float) Order::where('status', 'completed')->sum('total_amount'),
            'ordersToday' => (int) Order::whereRaw('DATE(COALESCE(ordered_at, created_at)) = ?', [now()->toDateString()])->count(),
            'publishedPosts' => (int) Post::where('is_published', true)->count(),
            'activeProducts' => (int) Product::count(),
        ];

        $trendStart = Carbon::now()->subDays(6)->startOfDay();
        $trendRows = Order::selectRaw('DATE(COALESCE(ordered_at, created_at)) as day, COUNT(*) as total_orders, COALESCE(SUM(total_amount), 0) as revenue')
            ->whereRaw('COALESCE(ordered_at, created_at) >= ?', [$trendStart->toDateTimeString()])
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $trendLabels = [];
        $ordersTrendData = [];
        $revenueTrendData = [];

        for ($i = 0; $i < 7; $i++) {
            $day = $trendStart->copy()->addDays($i);
            $key = $day->toDateString();
            $trendLabels[] = $day->translatedFormat('M d');
            $ordersTrendData[] = (int) (optional($trendRows->get($key))->total_orders ?? 0);
            $revenueTrendData[] = (float) (optional($trendRows->get($key))->revenue ?? 0);
        }

        $statusCounts = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $statusBreakdown = collect(Order::STATUSES)
            ->mapWithKeys(function (string $status) use ($statusCounts) {
                return [$status => (int) ($statusCounts[$status] ?? 0)];
            });

        $statusLabels = $statusBreakdown->keys()
            ->map(fn ($status) => __(Str::headline($status)))
            ->values();

        $statusData = $statusBreakdown->values();

        $recentOrders = Order::with('product')
            ->orderByRaw('COALESCE(ordered_at, created_at) DESC')
            ->take(5)
            ->get();

        return view('admin.index', [
            'currencySymbol' => $currencySymbol,
            'metrics' => $metrics,
            'trendLabels' => $trendLabels,
            'ordersTrendData' => $ordersTrendData,
            'revenueTrendData' => $revenueTrendData,
            'statusLabels' => $statusLabels->all(),
            'statusData' => $statusData->all(),
            'recentOrders' => $recentOrders,
            'statusBreakdown' => $statusBreakdown,
        ]);
    }
}
