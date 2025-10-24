@extends('admin.layouts.app')

@push('css')
    <style>
        .detail-card {
            background: white;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .field-label {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .field-value {
            font-size: 16px;
            font-weight: 500;
            color: #212529;
            margin-bottom: 20px;
        }

        .metadata-section {
            background: #f8f9fa;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 20px;
        }

        .metadata-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 25px;
        }

        .back-link {
            text-decoration: none;
            color: #212529;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-link:hover {
            color: #0056b3;
            text-decoration: none;
        }
    </style>
@endpush

@section('contents')
    <section class="content">
        <div class="container-fluid">
            <div class="mb-4">
                <h2 class="mb-1">{{ $product->name }}</h2>
                <p class="text-muted">Product Details</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="detail-card">
                        <h5 class="section-title">
                            <i class="fas fa-box"></i> Basic Information
                        </h5>

                        <div class="field-label">Name</div>
                        <div class="field-value">{{ $product->name }}</div>

                        <div class="field-label">Price</div>
                        <div class="field-value">${{ number_format($product->price, 2) }}</div>

                        <div class="field-label">Stock</div>
                        <div class="field-value">{{ $product->stock }}</div>

                        <div class="field-label">Status</div>
                        <div class="field-value">
                            <span class="badge badge-{{ $product->status == 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </div>

                        @if ($product->image)
                            <div class="field-label">Image</div>
                            <div class="field-value">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                    style="max-width: 200px; border-radius: 8px;">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-card">
                        <h5 class="section-title">
                            <i class="fas fa-align-left"></i> Description
                        </h5>
                        @if ($product->description)
                            <p>{{ $product->description }}</p>
                        @else
                            <p class="text-muted font-italic">No description provided</p>
                        @endif
                    </div>

                    <div class="detail-card">
                        <h5 class="section-title">
                            <i class="fas fa-tags"></i> Categories & Details
                        </h5>

                        <div class="field-label">Category</div>
                        <div class="field-value">{{ $product->category->name ?? 'N/A' }}</div>

                        <div class="field-label">Brand</div>
                        <div class="field-value">{{ $product->brand->name ?? 'N/A' }}</div>

                        <div class="field-label">Unit</div>
                        <div class="field-value">{{ $product->unit->name ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <div class="metadata-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="metadata-title mb-0">Metadata</h5>
                    <a href="{{ route('product.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Products
                    </a>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="field-label">Created At</div>
                        <div class="field-value">
                            <i class="far fa-calendar"></i> {{ $product->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Last Updated</div>
                        <div class="field-value">
                            <i class="far fa-calendar"></i> {{ $product->updated_at->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
