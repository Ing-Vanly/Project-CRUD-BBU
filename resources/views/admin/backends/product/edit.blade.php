@extends('admin.layouts.app')

@push('css')
    <style>
        .image-preview {
            max-width: 100%;
            max-height: 200px;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
            margin-top: 10px;
        }
        .image-preview img {
            max-width: 100%;
            max-height: 150px;
            border-radius: 4px;
        }
        .current-image {
            text-align: center;
            margin-bottom: 15px;
        }
        .current-image img {
            max-width: 150px;
            max-height: 150px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
@endpush

@section('contents')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Edit Product') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('product.index') }}">{{ __('Products') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Edit') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Edit Product Information') }}</h3>
                        </div>
                        <div class="card-body">
                            <form class="form-material form-horizontal" action="{{ route('product.update', $product) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name">{{ __('Product Name') }} <span class="text-danger">*</span></label>
                                        <input type="text" id="name" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="{{ __('Enter product name') }}"
                                            value="{{ old('name', $product->name) }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="price">{{ __('Price') }} <span class="text-danger">*</span></label>
                                        <input type="number" id="price" name="price" step="0.01" min="0"
                                            class="form-control @error('price') is-invalid @enderror"
                                            placeholder="{{ __('Enter product price') }}"
                                            value="{{ old('price', $product->price) }}">
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="stock">{{ __('Stock Quantity') }} <span class="text-danger">*</span></label>
                                        <input type="number" id="stock" name="stock" min="0"
                                            class="form-control @error('stock') is-invalid @enderror"
                                            placeholder="{{ __('Enter stock quantity') }}"
                                            value="{{ old('stock', $product->stock) }}">
                                        @error('stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="status">{{ __('Status') }} <span class="text-danger">*</span></label>
                                        <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                                            <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                            <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="category_id">{{ __('Category') }}</label>
                                        <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                            <option value="">{{ __('Select Category') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="brand_id">{{ __('Brand') }}</label>
                                        <select id="brand_id" name="brand_id" class="form-control @error('brand_id') is-invalid @enderror">
                                            <option value="">{{ __('Select Brand') }}</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('brand_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="unit_id">{{ __('Unit') }}</label>
                                        <select id="unit_id" name="unit_id" class="form-control @error('unit_id') is-invalid @enderror">
                                            <option value="">{{ __('Select Unit') }}</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}" {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('unit_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-8 mb-3">
                                        <label for="description">{{ __('Description') }}</label>
                                        <textarea id="description" name="description"
                                            class="form-control @error('description') is-invalid @enderror"
                                            placeholder="{{ __('Enter product description') }}" rows="8">{{ old('description', $product->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="image">{{ __('Product Image') }}</label>

                                        @if($product->image)
                                            <div class="current-image">
                                                <label>{{ __('Current Image') }}</label>
                                                <div>
                                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                                                </div>
                                            </div>
                                        @endif

                                        <input type="file" id="image" name="image"
                                            class="form-control @error('image') is-invalid @enderror"
                                            accept="image/*"
                                            onchange="previewImage(event)">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">{{ __('Leave empty to keep current image. Accepted formats: JPG, PNG, GIF. Max size: 2MB') }}</small>

                                        <div class="image-preview" id="imagePreview" style="display: none;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                            <p class="mt-2 text-muted">{{ __('New image preview') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-3 text-right">
                                    <a href="{{ route('product.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> {{ __('Cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> {{ __('Update Product') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imagePreview');

            if (file) {
                preview.style.display = 'block';
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Image Preview">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                preview.innerHTML = `
                    <i class="fas fa-image fa-3x text-muted"></i>
                    <p class="mt-2 text-muted">{{ __('New image preview') }}</p>
                `;
            }
        }
    </script>
@endpush
