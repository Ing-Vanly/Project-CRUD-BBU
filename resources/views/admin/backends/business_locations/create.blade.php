@extends('admin.layouts.app')

@section('contents')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Create Business Location') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('business-location.index') }}">{{ __('Business Locations') }}</a></li>
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
                            <h3 class="card-title">{{ __('Business Details') }}</h3>
                        </div>
                        <div class="card-body">
                            @include('admin.backends.business_locations.partials.form', [
                                'formAction' => route('business-location.store'),
                                'formMethod' => 'POST',
                                'submitLabel' => __('Create Location'),
                                'businessLocation' => null,
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
