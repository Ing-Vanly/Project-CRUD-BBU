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
                <h2 class="mb-1">{{ $category->name }}</h2>
                <p class="text-muted">Category Details</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="detail-card">
                        <h5 class="section-title">
                            <i class="fas fa-folder"></i> Basic Information
                        </h5>

                        <div class="field-label">Name</div>
                        <div class="field-value">{{ $category->name }}</div>

                        <div class="field-label">Slug</div>
                        <div class="field-value">{{ $category->slug }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-card">
                        <h5 class="section-title">
                            <i class="fas fa-align-left"></i> Description
                        </h5>
                        @if($category->description)
                            <p>{{ $category->description }}</p>
                        @else
                            <p class="text-muted font-italic">No description provided</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="metadata-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="metadata-title mb-0">Metadata</h5>
                    <a href="{{ route('category.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Categories
                    </a>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="field-label">Created At</div>
                        <div class="field-value">
                            <i class="far fa-calendar"></i> {{ $category->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="field-label">Last Updated</div>
                        <div class="field-value">
                            <i class="far fa-calendar"></i> {{ $category->updated_at->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
