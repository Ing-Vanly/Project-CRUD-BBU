@extends('admin.layouts.app')

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.6.2/dist/select2-bootstrap4.min.css">
    @include('admin.backends.order.partials.form-styles')
@endpush

@section('contents')
    <section class="content-header">
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-0">
                            <h3 class="card-title">{{ __('Update order information') }}</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('order.update', $order) }}" method="POST">
                                @csrf
                                @method('PUT')
                                @include('admin.backends.order.partials.form', ['order' => $order])
                                <div class="d-flex justify-content-end mt-4">
                                    <a href="{{ route('order.index') }}" class="btn btn-secondary mr-2">
                                        <i class="fas fa-arrow-left mr-1"></i> {{ __('Back') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i> {{ __('Update Order') }}
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @include('admin.backends.order.partials.form-scripts')
@endpush
