@extends('admin.layouts.app')

@push('css')
    <style>
        .loading-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.85);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            border-radius: 0.25rem;
        }

        .loading-content {
            text-align: center;
            color: #6c757d;
        }

        .loading-spinner {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

@section('contents')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Business Locations') }}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('business-location.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-1"></i> {{ __('Add Location') }}
                    </a>
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
                            <div class="row align-items-center">
                                <div class="col-12 col-md-6">
                                    <h3 class="card-title mb-0">{{ __('Location Directory') }}</h3>
                                </div>
                                <div class="col-12 col-md-6 text-md-right mt-2 mt-md-0">
                                    <small class="text-muted">{{ __('Manage branding details for each business location') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="searchFilter" class="form-label text-muted mb-1">{{ __('Search') }}</label>
                                    <input type="text" id="searchFilter" class="form-control" placeholder="{{ __('Search by name, email, phone or address') }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="createdFilter" class="form-label text-muted mb-1">{{ __('Created Date') }}</label>
                                    <input type="date" id="createdFilter" class="form-control">
                                </div>
                                <div class="col-md-3 mb-3 d-flex align-items-end">
                                    <button class="btn btn-outline-secondary w-100" id="resetFilters">
                                        <i class="fas fa-undo mr-1"></i> {{ __('Reset Filters') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="position-relative" id="table-container">
                            @include('admin.backends.business_locations.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        const indexUrl = "{{ route('business-location.index') }}";
        let filterTimeout;

        const fetchLocations = () => {
            const search = $('#searchFilter').val();
            const created = $('#createdFilter').val();

            $.ajax({
                type: 'GET',
                url: indexUrl,
                data: {
                    search,
                    created
                },
                beforeSend: function() {
                    showLoadingOverlay();
                },
                success: function(response) {
                    if (response.view) {
                        $('.table-wrapper').replaceWith(response.view);
                    }
                },
                error: function() {
                    toastr.error("{{ __('Unable to fetch business locations. Please try again.') }}");
                },
                complete: function() {
                    hideLoadingOverlay();
                }
            });
        };

        const showLoadingOverlay = () => {
            if (!$('#loading-overlay').length) {
                $('#table-container').append(`
                    <div class="loading-overlay" id="loading-overlay">
                        <div class="loading-content">
                            <div class="loading-spinner">
                                <i class="fas fa-circle-notch"></i>
                            </div>
                            <div>{{ __('Loading...') }}</div>
                        </div>
                    </div>
                `);
            }
        };

        const hideLoadingOverlay = () => {
            $('#loading-overlay').remove();
        };

        $('#searchFilter').on('keyup', function() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(fetchLocations, 400);
        });

        $('#createdFilter').on('change', fetchLocations);

        $('#resetFilters').on('click', function() {
            $('#searchFilter').val('');
            $('#createdFilter').val('');
            fetchLocations();
        });

        $(document).on('click', '.btn-delete-location', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const deleteForm = $(`.form-delete-${id}`);

            const Confirmation = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success mr-2',
                    cancelButton: 'btn btn-danger mr-2'
                },
                buttonsStyling: false
            });

            Confirmation.fire({
                title: "{{ __('Are you sure?') }}",
                text: "{{ __('This action cannot be undone.') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "{{ __('Yes, delete it!') }}",
                cancelButtonText: "{{ __('Cancel') }}"
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: deleteForm.attr('action'),
                    data: deleteForm.serialize(),
                    success: function(response) {
                        if (response.status === 1) {
                            $('.table-wrapper').replaceWith(response.view);
                            toastr.success(response.msg);
                        } else {
                            toastr.error(response.msg || "{{ __('Something went wrong.') }}");
                        }
                    },
                    error: function() {
                        toastr.error("{{ __('Unable to delete location. Please try again.') }}");
                    }
                });
            });
        });
    </script>
@endpush
