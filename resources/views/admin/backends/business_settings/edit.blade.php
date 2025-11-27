@extends('admin.layouts.app')
@section('contents')
    <section class="content-header">
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
