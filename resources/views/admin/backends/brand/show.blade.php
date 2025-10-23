@extends('admin.layouts.app')

@push('css')
    <style>
        .brand-logo {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .brand-logo img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .brand-info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
        }
        .brand-info-card h2 {
            margin-bottom: 10px;
            font-weight: 300;
        }
        .brand-info-card .slug {
            background: rgba(255,255,255,0.2);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9em;
            display: inline-block;
        }
        .description-card {
            background: white;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            padding: 20px;
        }
        .meta-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
        }
    </style>
@endpush

@section('contents')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Brand Details') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('brand.index') }}">{{ __('Brands') }}</a></li>
                        <li class="breadcrumb-item active">{{ $brand->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="brand-info-card">
                        <h2>{{ $brand->name }}</h2>
                        <span class="slug">{{ $brand->slug }}</span>
                    </div>

                    @if($brand->description)
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Description') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="description-card">
                                    <p class="mb-0">{{ $brand->description }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-body text-center text-muted">
                                <i class="fas fa-info-circle fa-2x mb-3"></i>
                                <p>{{ __('No description available for this brand.') }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-md-4">
                    @if($brand->logo)
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Brand Logo') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="brand-logo">
                                    <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}">
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('Brand Logo') }}</h3>
                            </div>
                            <div class="card-body text-center text-muted">
                                <i class="fas fa-image fa-3x mb-3"></i>
                                <p>{{ __('No logo uploaded for this brand.') }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Brand Information') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="meta-info">
                                <div class="row">
                                    <div class="col-12">
                                        <strong>{{ __('Created:') }}</strong><br>
                                        <span class="text-muted">{{ $brand->created_at->format('M d, Y \a\t H:i') }}</span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <strong>{{ __('Last Updated:') }}</strong><br>
                                        <span class="text-muted">{{ $brand->updated_at->format('M d, Y \a\t H:i') }}</span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <strong>{{ __('Slug:') }}</strong><br>
                                        <code>{{ $brand->slug }}</code>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Actions') }}</h3>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('brand.edit', $brand) }}" class="btn btn-primary btn-block">
                                <i class="fas fa-edit"></i> {{ __('Edit Brand') }}
                            </a>
                            <a href="{{ route('brand.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-arrow-left"></i> {{ __('Back to Brands') }}
                            </a>
                            <button type="button" class="btn btn-danger btn-block btn-delete" data-id="{{ $brand->id }}">
                                <i class="fas fa-trash"></i> {{ __('Delete Brand') }}
                            </button>
                            
                            <form action="{{ route('brand.destroy', $brand) }}" method="POST" class="d-none form-delete-{{ $brand->id }}">
                                @csrf
                                @method('DELETE')
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
                    $(`.form-delete-${$(this).data('id')}`).submit();
                }
            });
        });
    </script>
@endpush