<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->get();
        $statuses = Order::STATUSES;

        return view('admin.backends.order.index', compact('products', 'statuses'));
    }

    public function data(Request $request)
    {
        $orders = $this->filteredOrders($request)->get();

        return response()->json([
            'data' => $orders->map(fn (Order $order) => $this->formatOrderRow($order)),
        ]);
    }

    public function create()
    {
        $products = Product::where('status', 'active')->orderBy('name')->get();
        $statuses = Order::STATUSES;

        return view('admin.backends.order.create', compact('products', 'statuses'));
    }

    public function store(StoreOrderRequest $request)
    {
        $product = Product::findOrFail($request->input('product_id'));

        $order = Order::create([
            'product_id' => $product->id,
            'customer_name' => $request->input('customer_name'),
            'customer_email' => $request->input('customer_email'),
            'customer_phone' => $request->input('customer_phone'),
            'quantity' => $request->input('quantity'),
            'unit_price' => $product->price,
            'status' => $request->input('status', 'pending'),
            'ordered_at' => Carbon::parse($request->input('ordered_at')),
            'notes' => $request->input('notes'),
        ]);

        $product->decrement('stock', $order->quantity);

        return redirect()->route('order.index')
            ->with('success', 1)
            ->with('msg', __('Order #:number created successfully.', ['number' => $order->order_number]));
    }

    public function show(Order $order)
    {
        $order->load('product');

        return view('admin.backends.order.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $products = Product::orderBy('name')->get();
        $statuses = Order::STATUSES;

        return view('admin.backends.order.edit', compact('order', 'products', 'statuses'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $newProduct = Product::findOrFail($request->input('product_id'));

        $this->syncStockLevels($order, $newProduct, (int) $request->input('quantity'));

        $unitPrice = $order->product && $order->product->is($newProduct)
            ? $order->unit_price
            : $newProduct->price;

        $order->update([
            'product_id' => $newProduct->id,
            'customer_name' => $request->input('customer_name'),
            'customer_email' => $request->input('customer_email'),
            'customer_phone' => $request->input('customer_phone'),
            'quantity' => $request->input('quantity'),
            'unit_price' => $unitPrice,
            'status' => $request->input('status', $order->status),
            'ordered_at' => Carbon::parse($request->input('ordered_at')),
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('order.show', $order)
            ->with('success', 1)
            ->with('msg', __('Order #:number updated successfully.', ['number' => $order->order_number]));
    }

    public function destroy(Order $order)
    {
        if ($order->product) {
            $order->product->increment('stock', $order->quantity);
        }

        $order->delete();

        if (request()->ajax()) {
            return response()->json([
                'status' => 1,
                'message' => __('Order deleted successfully.'),
            ]);
        }

        return redirect()->route('order.index')
            ->with('success', 1)
            ->with('msg', __('Order deleted successfully.'));
    }

    public function export(Request $request)
    {
        $orders = $this->filteredOrders($request)->get();
        $filename = 'orders-' . now()->format('YmdHis') . '.xls';

        return response()->streamDownload(function () use ($orders) {
            echo '<table border="1">';
            echo '<thead><tr>';
            foreach ($this->exportHeaders() as $header) {
                echo '<th>' . htmlspecialchars($header, ENT_QUOTES, 'UTF-8') . '</th>';
            }
            echo '</tr></thead><tbody>';
            foreach ($orders as $order) {
                echo '<tr>';
                foreach ($this->convertOrderForExport($order) as $value) {
                    echo '<td>' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '</td>';
                }
                echo '</tr>';
            }
            echo '</tbody></table>';
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
        ]);
    }

    protected function filteredOrders(Request $request)
    {
        $query = Order::with('product')->orderByDesc('ordered_at');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }

        if ($request->filled('filter_type')) {
            $this->applyDateFilter($query, $request);
        }

        if ($request->filled('date_range')) {
            $this->applyDateRangeFilter($query, $request->input('date_range'));
        }

        return $query;
    }

    protected function applyDateFilter($query, Request $request): void
    {
        $type = $request->input('filter_type');

        if ($type === 'day' && $request->filled('filter_day')) {
            $query->whereDate('ordered_at', Carbon::parse($request->input('filter_day')));
        }

        if ($type === 'month' && $request->filled('filter_month')) {
            $monthInput = $request->input('filter_month') . '-01';
            $date = Carbon::parse($monthInput);
            $query->whereYear('ordered_at', $date->year)
                ->whereMonth('ordered_at', $date->month);
        }

        if ($type === 'year' && $request->filled('filter_year')) {
            $query->whereYear('ordered_at', (int) $request->input('filter_year'));
        }
    }

    protected function applyDateRangeFilter($query, string $range): void
    {
        $segments = array_filter(array_map('trim', explode(' - ', $range)));

        if (count($segments) === 2) {
            [$start, $end] = $segments;

            $query->whereBetween('ordered_at', [
                Carbon::parse($start)->startOfDay(),
                Carbon::parse($end)->endOfDay(),
            ]);
        }
    }

    protected function formatOrderRow(Order $order): array
    {
        return [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'customer_name' => $order->customer_name,
            'customer_email' => $order->customer_email ?? '--',
            'product_name' => optional($order->product)->name ?? __('N/A'),
            'quantity' => $order->quantity,
            'unit_price' => number_format($order->unit_price, 2),
            'total_amount' => number_format($order->total_amount, 2),
            'status' => ucfirst($order->status),
            'status_badge' => sprintf(
                '<span class="badge badge-%s text-uppercase">%s</span>',
                $this->statusColor($order->status),
                ucfirst($order->status)
            ),
            'ordered_at' => $order->ordered_at?->format('M d, Y h:i A') ?? '--',
            'actions' => view('admin.backends.order.partials.actions', ['order' => $order])->render(),
        ];
    }

    protected function statusColor(string $status): string
    {
        return match ($status) {
            'completed' => 'success',
            'processing' => 'primary',
            'cancelled' => 'danger',
            default => 'warning',
        };
    }

    protected function convertOrderForExport(Order $order): array
    {
        return [
            $order->order_number,
            optional($order->product)->name ?? __('N/A'),
            $order->customer_name,
            $order->customer_email ?? '',
            $order->customer_phone ?? '',
            $order->quantity,
            number_format($order->unit_price, 2),
            number_format($order->total_amount, 2),
            ucfirst($order->status),
            $order->ordered_at?->format('Y-m-d H:i'),
        ];
    }

    protected function exportHeaders(): array
    {
        return [
            __('Order #'),
            __('Product'),
            __('Customer Name'),
            __('Customer Email'),
            __('Customer Phone'),
            __('Quantity'),
            __('Unit Price'),
            __('Total Amount'),
            __('Status'),
            __('Ordered At'),
        ];
    }

    protected function syncStockLevels(Order $order, Product $newProduct, int $newQuantity): void
    {
        $currentProduct = $order->product;

        if ($currentProduct && $currentProduct->is($newProduct)) {
            $difference = $newQuantity - $order->quantity;

            if ($difference > 0) {
                $newProduct->decrement('stock', $difference);
            } elseif ($difference < 0) {
                $newProduct->increment('stock', abs($difference));
            }
        } else {
            if ($currentProduct) {
                $currentProduct->increment('stock', $order->quantity);
            }

            $newProduct->decrement('stock', $newQuantity);
        }
    }
}
