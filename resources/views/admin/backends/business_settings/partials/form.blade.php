@php($setting = $businessSetting ?? null)
@php($currencyOptions = [
    'USD' => 'USD - United States Dollar',
    'EUR' => 'EUR - Euro',
    'GBP' => 'GBP - British Pound',
    'KHR' => 'KHR - Cambodian Riel',
    'THB' => 'THB - Thai Baht',
    'VND' => 'VND - Vietnamese Dong',
])
@php($timezones = \DateTimeZone::listIdentifiers())

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
            <input type="text" id="name" name="name" maxlength="255"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', optional($setting)->name) }}"
                placeholder="{{ __('Enter the name of your business') }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">{{ __('Business Email') }}</label>
            <input type="email" id="email" name="email" maxlength="255"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', optional($setting)->email) }}"
                placeholder="{{ __('Primary contact email') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="phone" class="form-label">{{ __('Phone Number') }}</label>
            <input type="text" id="phone" name="phone" maxlength="50"
                class="form-control @error('phone') is-invalid @enderror"
                value="{{ old('phone', optional($setting)->phone) }}"
                placeholder="{{ __('Business hotline or reception number') }}">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="website" class="form-label">{{ __('Website') }}</label>
            <input type="url" id="website" name="website" maxlength="255"
                class="form-control @error('website') is-invalid @enderror"
                value="{{ old('website', optional($setting)->website) }}"
                placeholder="https://example.com">
            @error('website')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-12 mb-3">
            <label for="address" class="form-label">{{ __('Address') }}</label>
            <textarea id="address" name="address" rows="3" class="form-control @error('address') is-invalid @enderror"
                placeholder="{{ __('Full business address including city and country') }}">{{ old('address', optional($setting)->address) }}</textarea>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="currency_code" class="form-label">{{ __('Currency') }} <span class="text-danger">*</span></label>
            <select id="currency_code" name="currency_code"
                class="form-control @error('currency_code') is-invalid @enderror">
                @php($currentCurrency = strtoupper(old('currency_code', optional($setting)->currency_code ?? 'USD')))
                @foreach ($currencyOptions as $code => $label)
                    <option value="{{ $code }}" @selected($currentCurrency === $code)>{{ $label }}</option>
                @endforeach
                @if ($currentCurrency && !array_key_exists($currentCurrency, $currencyOptions))
                    <option value="{{ $currentCurrency }}" selected>{{ $currentCurrency }}</option>
                @endif
            </select>
            <small class="form-text text-muted">{{ __('Choose the default currency for pricing and reports.') }}</small>
            @error('currency_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="timezone" class="form-label">{{ __('Timezone') }} <span class="text-danger">*</span></label>
            <select id="timezone" name="timezone"
                class="form-control @error('timezone') is-invalid @enderror">
                @php($selectedTimezone = old('timezone', optional($setting)->timezone ?? config('app.timezone')))
                @foreach ($timezones as $tz)
                    <option value="{{ $tz }}" @selected($selectedTimezone === $tz)>{{ $tz }}</option>
                @endforeach
            </select>
            <small class="form-text text-muted">{{ __('Controls reporting periods and scheduling defaults.') }}</small>
            @error('timezone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-12 mb-3">
            <label for="footer_text" class="form-label">{{ __('Footer Text') }}</label>
            <textarea id="footer_text" name="footer_text" rows="4"
                class="form-control @error('footer_text') is-invalid @enderror"
                placeholder="{{ __('Optional message shown on receipts and customer-facing pages') }}">{{ old('footer_text', optional($setting)->footer_text) }}</textarea>
            @error('footer_text')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="logo" class="form-label">{{ __('Logo') }}</label>
            <input type="file" id="logo" name="logo" accept="image/*"
                class="form-control @error('logo') is-invalid @enderror">
            <small class="form-text text-muted">{{ __('Square image recommended, maximum 2MB.') }}</small>
            @error('logo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="media-preview mt-2" id="logoPreview">
                @if ($setting && $setting->logo)
                    <img src="{{ asset($setting->logo) }}" alt="{{ __('Current logo') }}">
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
            <small class="form-text text-muted">{{ __('32x32 icon recommended, maximum 1MB.') }}</small>
            @error('favicon')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="media-preview mt-2" id="faviconPreview">
                @if ($setting && $setting->favicon)
                    <img src="{{ asset($setting->favicon) }}" alt="{{ __('Current favicon') }}">
                @else
                    <div class="text-muted text-center">
                        <i class="fas fa-star fa-2x mb-2"></i>
                        <p class="mb-0">{{ __('Favicon preview will appear here') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="form-group text-right mt-3 mb-0">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i> {{ __('Save Settings') }}
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
