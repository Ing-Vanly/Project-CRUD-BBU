@extends('admin.layouts.app')

@push('css')
    <style>
        .content-editor {
            min-height: 200px;
        }
        .logo-preview {
            max-width: 200px;
            max-height: 200px;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
        }
        .logo-preview img {
            max-width: 100%;
            max-height: 150px;
            border-radius: 4px;
        }
        .current-logo {
            text-align: center;
            margin-bottom: 15px;
        }
        .current-logo img {
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
                    <h1>{{ __('Edit Brand') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('brand.index') }}">{{ __('Brands') }}</a></li>
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
                            <h3 class="card-title">{{ __('Edit Brand Information') }}</h3>
                        </div>
                        <div class="card-body">
                            <form class="form-material form-horizontal" action="{{ route('brand.update', $brand) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="name">{{ __('Brand Name') }} <span class="text-danger">*</span></label>
                                            <input type="text" id="name" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="{{ __('Enter brand name') }}" 
                                                value="{{ old('name', $brand->name) }}">
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="slug">{{ __('Slug') }}</label>
                                            <input type="text" id="slug" name="slug"
                                                class="form-control @error('slug') is-invalid @enderror"
                                                placeholder="{{ __('Auto-generated from name or enter custom') }}"
                                                value="{{ old('slug', $brand->slug) }}">
                                            @error('slug')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <small class="form-text text-muted">{{ __('Leave empty to auto-generate from name') }}</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">{{ __('Description') }}</label>
                                            <textarea id="description" name="description"
                                                class="form-control @error('description') is-invalid @enderror"
                                                placeholder="{{ __('Enter brand description...') }}"
                                                rows="5">{{ old('description', $brand->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">{{ __('Brand Logo') }}</h3>
                                            </div>
                                            <div class="card-body">
                                                @if($brand->logo)
                                                    <div class="current-logo">
                                                        <label>{{ __('Current Logo') }}</label>
                                                        <div>
                                                            <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}">
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="form-group">
                                                    <label for="logo">{{ __('Upload New Logo') }}</label>
                                                    <input type="file" id="logo" name="logo"
                                                        class="form-control-file @error('logo') is-invalid @enderror"
                                                        accept="image/*"
                                                        onchange="previewLogo(event)">
                                                    @error('logo')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                    <small class="form-text text-muted">{{ __('Leave empty to keep current logo. Accepted formats: JPG, PNG, GIF. Max size: 2MB') }}</small>
                                                </div>

                                                <div class="logo-preview" id="logoPreview" style="display: none;">
                                                    <i class="fas fa-image fa-3x text-muted"></i>
                                                    <p class="mt-2 text-muted">{{ __('New logo preview') }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">{{ __('Brand Info') }}</h3>
                                            </div>
                                            <div class="card-body">
                                                <small class="text-muted">
                                                    <strong>{{ __('Created:') }}</strong> {{ $brand->created_at->format('M d, Y H:i') }}<br>
                                                    <strong>{{ __('Updated:') }}</strong> {{ $brand->updated_at->format('M d, Y H:i') }}
                                                </small>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                                <i class="fas fa-save"></i> {{ __('Update Brand') }}
                                            </button>
                                            <a href="{{ route('brand.index') }}" class="btn btn-secondary btn-lg btn-block">
                                                <i class="fas fa-arrow-left"></i> {{ __('Back to Brands') }}
                                            </a>
                                        </div>
                                    </div>
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
        let slugEdited = false;

        document.getElementById('slug').addEventListener('input', function() {
            slugEdited = true; // User typed manually
        });

        document.getElementById('name').addEventListener('input', function() {
            if (!slugEdited) {
                const slug = this.value.toLowerCase()
                    .replace(/[^a-z0-9 -]/g, '') // Remove invalid chars
                    .replace(/\s+/g, '-') // Replace spaces with -
                    .replace(/-+/g, '-') // Replace multiple - with single -
                    .trim('-'); // Trim - from start and end
                
                document.getElementById('slug').value = slug;
            }
        });

        function previewLogo(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('logoPreview');
            
            if (file) {
                preview.style.display = 'block';
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Logo Preview">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                preview.innerHTML = `
                    <i class="fas fa-image fa-3x text-muted"></i>
                    <p class="mt-2 text-muted">{{ __('New logo preview') }}</p>
                `;
            }
        }
    </script>
@endpush