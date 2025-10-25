@php($location = $businessLocation ?? null)

<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="form-material form-horizontal">
    @csrf
    @isset($formMethod)
        @if (strtoupper($formMethod) !== 'POST')
            @method($formMethod)
        @endif
    @endisset

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">{{ __('Business Name') }} <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', optional($location)->name) }}" placeholder="{{ __('Enter business name') }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">{{ __('Contact Email') }} <span class="text-danger">*</span></label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', optional($location)->email) }}" placeholder="{{ __('Enter contact email') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="phone" class="form-label">{{ __('Phone Number') }}</label>
            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror"
                value="{{ old('phone', optional($location)->phone) }}" placeholder="{{ __('Enter phone number') }}">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="website" class="form-label">{{ __('Website') }}</label>
            <input type="url" id="website" name="website" class="form-control @error('website') is-invalid @enderror"
                value="{{ old('website', optional($location)->website) }}" placeholder="https://example.com">
            @error('website')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-12 mb-3">
            <label for="address" class="form-label">{{ __('Address') }} <span class="text-danger">*</span></label>
            <textarea id="address" name="address" rows="3" class="form-control @error('address') is-invalid @enderror"
                placeholder="{{ __('Provide the full business address') }}">{{ old('address', optional($location)->address) }}</textarea>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-12 mb-3">
            <label for="footer_text" class="form-label">{{ __('Footer Text') }}</label>
            <textarea id="footer_text" name="footer_text" rows="4"
                class="form-control @error('footer_text') is-invalid @enderror"
                placeholder="{{ __('Add optional footer information that appears on customer-facing screens') }}">{{ old('footer_text', optional($location)->footer_text) }}</textarea>
            @error('footer_text')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="logo" class="form-label">{{ __('Logo') }}</label>
            <input type="file" id="logo" name="logo" accept="image/*" class="form-control @error('logo') is-invalid @enderror">
            <small class="form-text text-muted">{{ __('Recommended square image, max 2MB.') }}</small>
            @error('logo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="media-preview mt-2" id="logoPreview">
                @if ($location && $location->logo)
                    <img src="{{ asset($location->logo) }}" alt="{{ __('Current logo') }}">
                @else
                    <div class="text-muted text-center">
                        <i class="fas fa-image fa-2x mb-2"></i>
                        <p class="mb-0">{{ __('Logo preview will appear here') }}</p>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="favicon" class="form-label">{{ __('Favicon') }}</label>
            <input type="file" id="favicon" name="favicon" accept="image/*"
                class="form-control @error('favicon') is-invalid @enderror">
            <small class="form-text text-muted">{{ __('Small square icon (32x32), max 1MB.') }}</small>
            @error('favicon')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="media-preview mt-2" id="faviconPreview">
                @if ($location && $location->favicon)
                    <img src="{{ asset($location->favicon) }}" alt="{{ __('Current favicon') }}">
                @else
                    <div class="text-muted text-center">
                        <i class="fas fa-star fa-2x mb-2"></i>
                        <p class="mb-0">{{ __('Favicon preview will appear here') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="form-group text-right mt-3">
        <a href="{{ route('business-location.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('Back') }}
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> {{ $submitLabel ?? __('Save Changes') }}
        </button>
    </div>
</form>

@once
    @push('css')
        <style>
            .media-preview {
                border: 2px dashed #dee2e6;
                border-radius: 0.5rem;
                min-height: 160px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #f8f9fa;
                padding: 1rem;
            }

            .media-preview img {
                max-height: 140px;
                max-width: 100%;
                object-fit: contain;
            }
        </style>
    @endpush
@endonce

@once
    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const previewImage = (fileInputId, previewContainerId) => {
                    const input = document.getElementById(fileInputId);
                    const previewContainer = document.getElementById(previewContainerId);

                    if (!input || !previewContainer) {
                        return;
                    }

                    input.addEventListener('change', function(event) {
                        const file = event.target.files[0];

                        if (!file) {
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewContainer.innerHTML =
                                `<img src="${e.target.result}" alt="${fileInputId} preview">`;
                        };
                        reader.readAsDataURL(file);
                    });
                };

                previewImage('logo', 'logoPreview');
                previewImage('favicon', 'faviconPreview');
            });
        </script>
    @endpush
@endonce
