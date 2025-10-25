@extends('admin.layouts.app')

@section('contents')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $businessLocation->name }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('business-location.index') }}">{{ __('Business Locations') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Details') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">{{ __('Location Overview') }}</h3>
                            <div>
                                <a href="{{ route('business-location.edit', $businessLocation) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit mr-1"></i> {{ __('Edit') }}
                                </a>
                                <a href="{{ route('business-location.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left mr-1"></i> {{ __('Back') }}
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">{{ __('Business Name') }}</dt>
                                <dd class="col-sm-8">{{ $businessLocation->name }}</dd>

                                <dt class="col-sm-4">{{ __('Email') }}</dt>
                                <dd class="col-sm-8">
                                    <a href="mailto:{{ $businessLocation->email }}">{{ $businessLocation->email }}</a>
                                </dd>

                                <dt class="col-sm-4">{{ __('Phone') }}</dt>
                                <dd class="col-sm-8">{{ $businessLocation->phone ?: __('Not provided') }}</dd>

                                <dt class="col-sm-4">{{ __('Website') }}</dt>
                                <dd class="col-sm-8">
                                    @if ($businessLocation->website)
                                        <a href="{{ $businessLocation->website }}" target="_blank" rel="noopener">
                                            {{ $businessLocation->website }}
                                        </a>
                                    @else
                                        {{ __('Not provided') }}
                                    @endif
                                </dd>

                                <dt class="col-sm-4">{{ __('Address') }}</dt>
                                <dd class="col-sm-8">{{ $businessLocation->address }}</dd>

                                <dt class="col-sm-4">{{ __('Created At') }}</dt>
                                <dd class="col-sm-8">{{ $businessLocation->created_at->format('M d, Y h:i A') }}</dd>

                                <dt class="col-sm-4">{{ __('Last Updated') }}</dt>
                                <dd class="col-sm-8">{{ $businessLocation->updated_at->format('M d, Y h:i A') }}</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-0">{{ __('Footer Text') }}</h3>
                        </div>
                        <div class="card-body">
                            @if ($businessLocation->footer_text)
                                <p class="mb-0">{{ $businessLocation->footer_text }}</p>
                            @else
                                <p class="text-muted mb-0">{{ __('No footer text configured.') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title mb-0">{{ __('Logo') }}</h3>
                        </div>
                        <div class="card-body text-center">
                            @if ($businessLocation->logo)
                                <img src="{{ asset($businessLocation->logo) }}" class="img-fluid" alt="{{ __('Business logo') }}">
                            @else
                                <p class="text-muted mb-0">{{ __('No logo uploaded.') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-0">{{ __('Favicon') }}</h3>
                        </div>
                        <div class="card-body text-center">
                            @if ($businessLocation->favicon)
                                <img src="{{ asset($businessLocation->favicon) }}" class="img-fluid" style="max-width: 64px;" alt="{{ __('Business favicon') }}">
                            @else
                                <p class="text-muted mb-0">{{ __('No favicon uploaded.') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
