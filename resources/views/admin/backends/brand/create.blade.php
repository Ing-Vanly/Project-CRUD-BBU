@extends('admin.layouts.app')

@push('css')
    <style>
        .content-editor {
            min-height: 200px;
        }
        .logo-preview {
            max-width: 100%;
            max-height: 200px;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
            margin-top: 10px;
        }
        .logo-preview img {
            max-width: 100%;
            max-height: 150px;
            border-radius: 4px;
        }
    </style>
@endpush

@section('contents')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Create New Brand') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('brand.index') }}">{{ __('Brands') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Create') }}</li>
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
                            <h3 class="card-title">{{ __('Brand Information') }}</h3>
                        </div>
                        <div class="card-body">
                            <form class="form-material form-horizontal" action="{{ route('brand.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name">{{ __('Brand Name') }} <span class="text-danger">*</span></label>
                                        <input type="text" id="name" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="{{ __('Enter brand name') }}" 
                                            value="{{ old('name') }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="slug">{{ __('Slug') }}</label>
                                        <input type="text" id="slug" name="slug"
                                            class="form-control @error('slug') is-invalid @enderror"
                                            placeholder="{{ __('Auto-generated from name or enter custom') }}"
                                            value="{{ old('slug') }}">
                                        @error('slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">{{ __('Leave empty to auto-generate from name') }}</small>
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <label for="description">{{ __('Description') }}</label>
                                        <textarea id="description" name="description"
                                            class="form-control content-editor @error('description') is-invalid @enderror"
                                            placeholder="{{ __('Write your brand description here...') }}" rows="8">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="logo">{{ __('Brand Logo') }}</label>
                                        <input type="file" id="logo" name="logo"
                                            class="form-control @error('logo') is-invalid @enderror"
                                            accept="image/*"
                                            onchange="previewLogo(event)">
                                        @error('logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">{{ __('Accepted formats: JPG, PNG, GIF. Max size: 2MB') }}</small>
                                        
                                        <div class="logo-preview" id="logoPreview">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                            <p class="mt-2 text-muted">{{ __('Logo preview will appear here') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-3 text-right">
                                    <a href="{{ route('brand.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> {{ __('Cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> {{ __('Create Brand') }}
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
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Logo Preview">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = `
                    <i class="fas fa-image fa-3x text-muted"></i>
                    <p class="mt-2 text-muted">{{ __('Logo preview will appear here') }}</p>
                `;
            }
        }
    </script>
@endpush