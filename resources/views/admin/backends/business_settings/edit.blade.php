@extends('admin.layouts.app')

@section('contents')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Business Settings') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Business Settings') }}</li>
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
                            <h3 class="card-title mb-0">{{ __('Update business identity and defaults') }}</h3>
                        </div>
                        <div class="card-body">
                            @include('admin.backends.business_settings.partials.form', [
                                'formAction' => route('business-setting.update'),
                                'formMethod' => 'PUT',
                                'businessSetting' => $businessSetting,
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
