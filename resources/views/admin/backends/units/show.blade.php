@extends('admin.layouts.app')

@push('css')
    <style>
        .unit-content {
            line-height: 1.6;
        }
        .unit-meta {
            border-left: 4px solid #007bff;
            padding-left: 15px;
            background-color: #f8f9fa;
        }
    </style>
@endpush

@section('contents')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('View Unit') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('unit.index') }}">{{ __('Unit') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('View') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $unit->name }}</h3>
                            <div class="card-tools">
                                <div class="btn-group">
                                    <a href="{{ route('unit.index') }}" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="unit-content">
                                <h5>{{ __('Description') }}</h5>
                                <p>{{ $unit->description ?? __('No description available') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Unit Information') }}</h3>
                        </div>
                        <div class="card-body unit-meta">
                            <div class="mb-3">
                                <strong>{{ __('Slug:') }}</strong>
                                <span class="text-muted">{{ $unit->slug }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>{{ __('Created:') }}</strong>
                                <span class="text-muted">{{ $unit->created_at->format('M d, Y \a\t H:i') }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>{{ __('Last Updated:') }}</strong>
                                <span class="text-muted">{{ $unit->updated_at->format('M d, Y \a\t H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Actions') }}</h3>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('unit.edit', $unit->id) }}" class="btn btn-primary btn-block">
                                <i class="fas fa-edit"></i> {{ __('Edit Unit') }}
                            </a>
                            <form action="{{ route('unit.destroy', $unit->id) }}" method="POST" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-block btn-delete" data-id="{{ $unit->id }}">
                                    <i class="fas fa-trash"></i> {{ __('Delete Unit') }}
                                </button>
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
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();

            const Confirmation = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success mr-2',
                    cancelButton: 'btn btn-danger mr-2'
                },
                buttonsStyling: false
            });

            Confirmation.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).closest('form').submit();
                }
            });
        });
    </script>
@endpush
