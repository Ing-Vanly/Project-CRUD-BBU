@extends('admin.layouts.app')

@php
    $dateRangeValue = request('date_range');
    $dateRangeDisplay = null;

    if ($dateRangeValue) {
        $parts = explode('|', $dateRangeValue);
        if (count($parts) === 2) {
            try {
                $start = \Carbon\Carbon::parse($parts[0]);
                $end = \Carbon\Carbon::parse($parts[1]);
                $dateRangeDisplay = $start->format('M d, Y') . ' - ' . $end->format('M d, Y');
            } catch (\Throwable $th) {
                $dateRangeDisplay = null;
            }
        }
    }
@endphp

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <style>
        .loading-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.85);
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

        .date-range-trigger {
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            padding: 0.45rem 0.75rem;
            display: flex;
            align-items: center;
            min-height: calc(2.25rem + 2px);
            cursor: pointer;
            transition: border-color 0.15s ease-in-out;
        }

        .date-range-trigger:hover {
            border-color: #a2a6aa;
        }

        .date-range-trigger:focus {
            outline: none;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .date-range-trigger .icon {
            margin-right: 0.5rem;
            color: #6c757d;
        }

        .date-range-trigger .caret {
            margin-left: auto;
            color: #6c757d;
        }

        .date-range-trigger span {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
        {{-- <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>{{ __('Authors') }}</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Authors') }}</li>
                    </ol>
                </div>
            </div>
        </div> --}}
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h3 class="card-title mb-0">{{ __('Author Management') }}</h3>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a class="btn btn-primary" href="{{ route('author.create') }}">
                                        <i class="fa fa-plus-circle mr-1"></i>
                                        {{ __('Add Author') }}
                                    </a>
                                </div>
                            </div>

                            <div class="row mt-3 align-items-end">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control" id="searchFilter"
                                            placeholder="{{ __('Search by name, email or phone...') }}"
                                            value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <div class="d-flex justify-content-md-end">
                                            <div id="dateRangeTrigger"
                                                class="date-range-trigger flex-grow-1 flex-md-grow-0"
                                                role="button" tabindex="0"
                                                style="min-width: 570px;">
                                                <i class="far fa-calendar-alt icon"></i>
                                                <span id="dateRangeText"
                                                    class="{{ $dateRangeDisplay ? '' : 'text-muted' }}">
                                                    {{ $dateRangeDisplay ?? __('Select date range') }}
                                                </span>
                                                <i class="fas fa-caret-down caret ml-2"></i>
                                            </div>
                                        </div>
                                        <input type="hidden" id="dateRangeValue" value="{{ $dateRangeValue }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="position-relative" id="table-container">
                            @include('admin.backends.author.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();

            const confirmation = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success mr-2',
                    cancelButton: 'btn btn-danger mr-2'
                },
                buttonsStyling: false
            });

            confirmation.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const deleteForm = $(`.form-delete-${$(this).data('id')}`);
                    const formData = deleteForm.serialize();

                    $.ajax({
                        type: "DELETE",
                        url: deleteForm.attr('action'),
                        data: formData,
                        success: function(response) {
                            if (response.status === 1) {
                                $('.table-wrapper').replaceWith(response.view);
                                toastr.success(response.msg);
                            } else {
                                toastr.error(response.msg);
                            }
                        },
                        error: function() {
                            toastr.error('{{ __('Something went wrong. Please try again!') }}');
                        }
                    });
                }
            });
        });

        let filterTimeout;

        function performFilter() {
            $.ajax({
                type: "GET",
                url: "{{ route('author.index') }}",
                data: {
                    search: $('#searchFilter').val(),
                    date_range: $('#dateRangeValue').val()
                },
                beforeSend: function() {
                    showLoadingOverlay();
                },
                success: function(response) {
                    $('.table-wrapper').replaceWith(response.view);
                },
                error: function() {
                    toastr.error('{{ __('Something went wrong. Please try again!') }}');
                },
                complete: function() {
                    hideLoadingOverlay();
                }
            });
        }

        function showLoadingOverlay() {
            if (!$('#loading-overlay').length) {
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
        }

        function hideLoadingOverlay() {
            $('#loading-overlay').remove();
        }

        $('#searchFilter').on('keyup', function() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(performFilter, 400);
        });

        const placeholderText = '{{ __('Select date range') }}';
        const dateRangeTrigger = $('#dateRangeTrigger');
        const dateRangeText = $('#dateRangeText');
        const dateRangeValue = $('#dateRangeValue');
        const initialValue = dateRangeValue.val();
        let startDate = moment().startOf('month');
        let endDate = moment().endOf('month');

        if (initialValue && initialValue.includes('|')) {
            const parts = initialValue.split('|');
            const startMoment = moment(parts[0], 'YYYY-MM-DD');
            const endMoment = moment(parts[1], 'YYYY-MM-DD');

            if (startMoment.isValid() && endMoment.isValid()) {
                startDate = startMoment;
                endDate = endMoment;
                dateRangeText.text(`${startDate.format('MMM D, YYYY')} - ${endDate.format('MMM D, YYYY')}`);
                dateRangeText.removeClass('text-muted');
            } else {
                dateRangeText.text(placeholderText).addClass('text-muted');
                dateRangeValue.val('');
            }
        } else {
            dateRangeText.text(placeholderText).addClass('text-muted');
            dateRangeValue.val('');
        }

        function applyRange(start, end, trigger = true) {
            dateRangeText.text(`${start.format('MMM D, YYYY')} - ${end.format('MMM D, YYYY')}`);
            dateRangeText.removeClass('text-muted');
            dateRangeValue.val(`${start.format('YYYY-MM-DD')}|${end.format('YYYY-MM-DD')}`);
            if (trigger) {
                performFilter();
            }
        }

        dateRangeTrigger.daterangepicker({
            startDate: startDate,
            endDate: endDate,
            opens: 'left',
            autoUpdateInput: false,
            locale: {
                cancelLabel: '{{ __('Clear') }}'
            },
            ranges: {
                '{{ __('Today') }}': [moment(), moment()],
                '{{ __('Yesterday') }}': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '{{ __('Last 7 Days') }}': [moment().subtract(6, 'days'), moment()],
                '{{ __('Last 30 Days') }}': [moment().subtract(29, 'days'), moment()],
                '{{ __('This Month') }}': [moment().startOf('month'), moment().endOf('month')],
                '{{ __('Last Month') }}': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end) {
            applyRange(start, end);
        });

        if (initialValue) {
            applyRange(startDate, endDate, false);
        }

        function clearRange(trigger = true) {
            dateRangeText.text(placeholderText).addClass('text-muted');
            dateRangeValue.val('');
            if (trigger) {
                performFilter();
            }
        }

        dateRangeTrigger.on('cancel.daterangepicker', function() {
            clearRange();
        });
    </script>
@endpush
