@extends('admin.layouts.app')
@push('css')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 22px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(18px);
            -ms-transform: translateX(18px);
            transform: translateX(18px);
        }

        .slider.round {
            border-radius: 22px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
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
                    <h3>{{ __('Units') }}</h3>
                </div>
                <div class="col-sm-6" style="text-align: right">
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-6 col-xs-6 col-sm-6">
                                    <h3 class="card-title">{{ __('Unit Management') }}</h3>
                                </div>
                                <div class="col-6 col-xs-6 col-sm-6">
                                    @can('unit.create')
                                        <a class="btn btn-primary float-right" href="{{ route('unit.create') }}">
                                            <i class=" fa fa-plus-circle"></i>
                                            {{ __('Add New Unit') }}
                                        </a>
                                    @endcan
                                </div>
                            </div>

                            <!-- Filter Form -->
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control" id="searchFilter"
                                            placeholder="{{ __('Search by name...') }}" value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <input type="date" class="form-control" id="createdFilter"
                                            placeholder="{{ __('Filter by created date') }}" value="{{ request('created') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="position-relative" id="table-container">
                            @include('admin.backends.units.table')
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

                    var deleteForm = $(`.form-delete-${$(this).data('id')}`);
                    var formData = deleteForm.serialize();

                    $.ajax({
                        type: "DELETE",
                        url: deleteForm.attr('action'),
                        data: formData,
                        success: function(response) {
                            if (response.status == 1) {
                                $('.table-wrapper').replaceWith(response.view);
                                toastr.success(response.msg);
                            } else {
                                toastr.error(response.msg);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Something went wrong. Please try again!');
                        }
                    });
                }
            });
        });
        // Auto-filter functionality
        let filterTimeout;

        function performFilter() {
            const search = $('#searchFilter').val();
            const created = $('#createdFilter').val();

            $.ajax({
                type: "GET",
                url: "{{ route('unit.index') }}",
                data: {
                    search: search,
                    created: created
                },
                beforeSend: function() {
                    showLoadingOverlay();
                },
                success: function(response) {
                    $('.table-wrapper').replaceWith(response.view);
                },
                error: function(xhr) {
                    toastr.error('Something went wrong. Please try again!');
                },
                complete: function() {
                    hideLoadingOverlay();
                }
            });
        }

        function showLoadingOverlay() {
            $('#table-container').append(`
                <div class="loading-overlay" id="loading-overlay">
                    <div class="loading-content">
                        <div class="loading-spinner">
                            <i class="fas fa-circle-notch"></i>
                        </div>
                        <div>{{ __('Searching...') }}</div>
                    </div>
                </div>
            `);
        }

        function hideLoadingOverlay() {
            $('#loading-overlay').remove();
        }

        // Search input with debounce
        $('#searchFilter').on('keyup', function() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(function() {
                performFilter();
            }, 500);
        });

        // Created date filter - immediate filter
        $('#createdFilter').on('change', function() {
            performFilter();
        });

        let slugEdited = false;

        document.getElementById('slug').addEventListener('input', function() {
            slugEdited = true; // User typed manually
        });

        document.getElementById('name').addEventListener('input', function() {
            if (!slugEdited) {
                document.getElementById('slug').value = this.value;
            }
        });
    </script>
@endpush
