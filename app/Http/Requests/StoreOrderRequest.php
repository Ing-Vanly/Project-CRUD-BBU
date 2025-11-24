<?php

namespace App\Http\Requests;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'quantity' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(Order::STATUSES)],
            'ordered_at' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $productId = $this->input('product_id');
            $quantity = (int) $this->input('quantity');

            if (!$productId || !$quantity) {
                return;
            }

            $product = Product::find($productId);

            if (!$product) {
                return;
            }

            $availableStock = (int) $product->stock;

            if ($quantity > $availableStock) {
                $validator->errors()->add('quantity', __('Not enough stock is available for the selected product.'));
            }
        });
    }
}
