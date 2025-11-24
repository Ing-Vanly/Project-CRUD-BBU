@php
    $selectedProductId = old('product_id', optional($order)->product_id);
    $selectedStatus = old('status', optional($order)->status ?? 'pending');
    $orderedAtValue = old('ordered_at', optional(optional($order)->ordered_at)->format('Y-m-d H:i'));
    $orderedAtValue = $orderedAtValue ?: now()->format('Y-m-d H:i');
@endphp

<div class="order-form">
    <div class="row">
        <div class="col-lg-8">
            <div class="form-row">
                <div class="form-group col-md-6">
                <label for="product_id">{{ __('Product') }} <span class="text-danger">*</span></label>
                <select name="product_id" id="product_id"
                    class="form-control select2 @error('product_id') is-invalid @enderror"
                    data-placeholder="{{ __('Select product') }}">
                    <option value="">{{ __('Select product') }}</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                            data-stock="{{ $product->stock }}"
                            {{ (string) $selectedProductId === (string) $product->id ? 'selected' : '' }}>
                            {{ $product->name }} - ${{ number_format($product->price, 2) }}
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
                <div class="form-group col-md-6">
                <label for="quantity">{{ __('Quantity') }} <span class="text-danger">*</span></label>
                <input type="number" min="1" name="quantity" id="quantity"
                    class="form-control @error('quantity') is-invalid @enderror"
                    value="{{ old('quantity', optional($order)->quantity ?? 1) }}">
                @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
                <div class="form-group col-md-6">
                <label for="customer_name">{{ __('Customer Name') }} <span class="text-danger">*</span></label>
                <input type="text" name="customer_name" id="customer_name"
                    class="form-control @error('customer_name') is-invalid @enderror"
                    value="{{ old('customer_name', optional($order)->customer_name) }}"
                    placeholder="{{ __('Enter customer name') }}">
                @error('customer_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
                <div class="form-group col-md-6">
                <label for="customer_email">{{ __('Customer Email') }}</label>
                <input type="email" name="customer_email" id="customer_email"
                    class="form-control @error('customer_email') is-invalid @enderror"
                    value="{{ old('customer_email', optional($order)->customer_email) }}"
                    placeholder="{{ __('Enter customer email') }}">
                @error('customer_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
                <div class="form-group col-md-6">
                <label for="customer_phone">{{ __('Customer Phone') }}</label>
                <input type="text" name="customer_phone" id="customer_phone"
                    class="form-control @error('customer_phone') is-invalid @enderror"
                    value="{{ old('customer_phone', optional($order)->customer_phone) }}"
                    placeholder="{{ __('Enter customer phone') }}">
                @error('customer_phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
                <div class="form-group col-md-6">
                <label for="status">{{ __('Status') }} <span class="text-danger">*</span></label>
                <select name="status" id="status"
                    class="form-control select2 @error('status') is-invalid @enderror"
                    data-placeholder="{{ __('Select status') }}">
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}"
                            {{ $selectedStatus === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
                <div class="form-group col-md-6">
                <label for="ordered_at">{{ __('Order Date') }} <span class="text-danger">*</span></label>
                <input type="text" name="ordered_at" id="ordered_at"
                    class="form-control order-datetime @error('ordered_at') is-invalid @enderror"
                    value="{{ $orderedAtValue }}" autocomplete="off">
                @error('ordered_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
                <div class="form-group col-md-12">
                <label for="notes">{{ __('Notes') }}</label>
                <textarea name="notes" id="notes" rows="4"
                    class="form-control @error('notes') is-invalid @enderror"
                    placeholder="{{ __('Add any additional details') }}">{{ old('notes', optional($order)->notes) }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100 border shadow-sm">
                <div class="card-body">
                    <h5 class="text-primary text-uppercase font-weight-bold">{{ __('Order Summary') }}</h5>
                    <p class="text-muted">{{ __('Review stock, pricing, and totals before saving.') }}</p>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>{{ __('Available Stock') }}</span>
                        <span class="font-weight-bold" id="availableStockText">0</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span>{{ __('Unit Price') }}</span>
                        <span class="font-weight-bold" id="unitPriceText">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span>{{ __('Total Amount') }}</span>
                        <span class="font-weight-bold h5 mb-0" id="totalAmountText">$0.00</span>
                    </div>
                    <small class="text-muted d-block mt-3">{{ __('Prices update automatically based on the selected product.') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
