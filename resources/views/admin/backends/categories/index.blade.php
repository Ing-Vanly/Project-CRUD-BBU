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
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endpush

@section('contents')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>{{ __('Posts') }}</h3>
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
                                    <h3 class="card-title">{{ __('Post Management') }}</h3>
                                </div>
                                <div class="col-6 col-xs-6 col-sm-6">
                                    <a class="btn btn-primary float-right" href="{{ route('post.create') }}">
                                        <i class=" fa fa-plus-circle"></i>
                                        {{ __('Add New Post') }}
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Filter Form -->
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control" id="searchFilter" placeholder="{{ __('Search by title...') }}" value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <select class="form-control" id="statusFilter">
                                            <option value="">{{ __('All Status') }}</option>
                                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <select class="form-control" id="publishedFilter">
                                            <option value="">{{ __('All Published') }}</option>
                                            <option value="1" {{ request('published') == '1' ? 'selected' : '' }}>{{ __('Published') }}</option>
                                            <option value="0" {{ request('published') == '0' ? 'selected' : '' }}>{{ __('Unpublished') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="position-relative" id="table-container">
                            @include('admin.backends.post.table')
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

        // Handle published status toggle
        $(document).on('change', '.toggle-published', function() {
            const postId = $(this).data('id');
            const isChecked = $(this).is(':checked');
            const toggle = $(this);
            const badge = toggle.closest('td').find('.badge');

            // Disable toggle during request
            toggle.prop('disabled', true);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: `/post/${postId}/toggle-published`,
                success: function(response) {
                    if (response.status == 1) {
                        // Update badge
                        if (response.is_published) {
                            badge.removeClass('badge-warning').addClass('badge-primary').text('Published');
                        } else {
                            badge.removeClass('badge-primary').addClass('badge-warning').text('Unpublished');
                        }
                        toastr.success(response.msg);
                    } else {
                        // Revert toggle state
                        toggle.prop('checked', !isChecked);
                        toastr.error(response.msg);
                    }
                },
                error: function(xhr) {
                    // Revert toggle state
                    toggle.prop('checked', !isChecked);
                    toastr.error('Something went wrong. Please try again!');
                },
                complete: function() {
                    // Re-enable toggle
                    toggle.prop('disabled', false);
                }
            });
        });

        // Auto-filter functionality
        let filterTimeout;

        function performFilter() {
            const search = $('#searchFilter').val();
            const status = $('#statusFilter').val();
            const published = $('#publishedFilter').val();

            $.ajax({
                type: "GET",
                url: "{{ route('post.index') }}",
                data: {
                    search: search,
                    status: status,
                    published: published
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

        // Status and Published dropdowns - immediate filter
        $('#statusFilter, #publishedFilter').on('change', function() {
            performFilter();
        });
    </script>
@endpush