@extends('admin.layouts.app')

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.6.2/dist/select2-bootstrap4.min.css">
    <style>
        .card-header .card-title {
            font-weight: 600;
        }

        .card-header .card-title small {
            font-weight: normal;
            margin-left: 0.25rem;
            color: #6b7280;
        }

        .card-header .card-tools .btn {
            margin-left: 0.5rem;
        }

        .filters-panel {
            padding: 0.5rem 0 0.5rem;
        }

        .filters-panel label {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .filters-panel .form-control,
        .filters-panel .select2-container--bootstrap4 .select2-selection {
            background: #fff;
        }

        .filters-panel .select2-container--bootstrap4 .select2-selection--single {
            border: 1px solid #ced4da;
            min-height: calc(2.25rem + 2px);
            border-radius: 0.25rem;
            display: flex;
            align-items: center;
            padding: 0 0.25rem 0 0.75rem;
        }

        .filters-panel .select2-container--bootstrap4 .select2-selection__rendered {
            padding-left: 0;
            line-height: 1.5;
        }

        .filters-panel .select2-container--bootstrap4 .select2-selection__arrow {
            height: calc(2.25rem + 2px);
            right: 0.5rem;
        }

        #ordersTable {
            width: 100% !important;
        }

        #ordersTable thead th {
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #6b7280;
            border-top: none;
        }

        #ordersTable tbody td {
            vertical-align: middle;
        }

        .datatable-card .dataTables_info {
            padding-left: 1rem;
        }

        .datatable-card .dataTables_paginate {
            padding-right: 1rem;
        }

        .datatable-empty-state {
            padding: 2rem 1rem;
            text-align: center;
            color: #6b7280;
        }

        .datatable-empty-state-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            color: #d1d5db;
        }

        .datatable-empty-state h5 {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .datatable-empty-state p {
            margin-bottom: 0;
        }

        .loading-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            border-radius: 0.5rem;
        }

        .loading-content {
            text-align: center;
            color: #4b5563;
        }

        .loading-spinner {
            display: inline-flex;
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
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">{{ __('Filters') }}</h3>
                            <div class="ml-auto">
                                <button type="button"
                                    class="btn btn-outline-secondary d-flex align-items-center"
                                    id="toggleFilters">
                                    <i class="fas fa-filter mr-2"></i>
                                    <span class="d-none d-sm-inline">{{ __('Toggle') }}</span>
                                </button>
                            </div>
                        </div>
                        <div class="collapse filters-panel show" id="filtersCollapse">
                            <form id="orderFilters" class="p-3 pt-1">
                                <div class="form-row">
                                        <div class="form-group col-md-4 filter-field d-none" data-filter-target="day">
                                            <label for="filterDay">{{ __('Choose Day') }}</label>
                                            <input type="date" class="form-control" name="filter_day" id="filterDay">
                                        </div>
                                        <div class="form-group col-md-4 filter-field d-none" data-filter-target="month">
                                            <label for="filterMonth">{{ __('Choose Month') }}</label>
                                            <input type="month" class="form-control" name="filter_month" id="filterMonth">
                                        </div>
                                        <div class="form-group col-md-4 filter-field d-none" data-filter-target="year">
                                            <label for="filterYear">{{ __('Choose Year') }}</label>
                                            <input type="number" class="form-control" name="filter_year" id="filterYear"
                                                min="2000" max="{{ now()->year + 1 }}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="statusFilter">{{ __('Status') }}</label>
                                            <select class="form-control select2" name="status" id="statusFilter"
                                                data-placeholder="{{ __('All Statuses') }}">
                                                <option value="">{{ __('All Statuses') }}</option>
                                                @foreach ($statuses as $status)
                                                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="productFilter">{{ __('Product') }}</label>
                                            <select class="form-control select2" name="product_id" id="productFilter"
                                                data-placeholder="{{ __('All Products') }}">
                                                <option value="">{{ __('All Products') }}</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="dateRange">{{ __('Custom Date Range') }}</label>
                                            <input type="text" name="date_range" id="dateRange" class="form-control"
                                                placeholder="{{ __('Select range') }}" autocomplete="off">
                                        </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card datatable-card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h3 class="card-title mb-0">
                                        {{ __('Order List') }}
                                    </h3>
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <div
                                        class="card-tools d-flex justify-content-md-end flex-wrap align-items-center w-100">
                                        <button type="button" class="btn btn-outline-secondary mb-2 mb-sm-0 mr-sm-2"
                                            id="exportOrders">
                                            <i class="fas fa-file-excel mr-2"></i> {{ __('Export Excel') }}
                                        </button>
                                        <a href="{{ route('order.create') }}" class="btn btn-primary mb-2 mb-sm-0">
                                            <i class="fas fa-plus-circle mr-2"></i> {{ __('Create Order') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="position-relative" id="table-container">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" id="ordersTable">
                                        <thead class="text-uppercase">
                                            <tr>
                                                <th>{{ __('Order #') }}</th>
                                                <th>{{ __('Customer') }}</th>
                                                <th>{{ __('Product') }}</th>
                                                <th class="text-center">{{ __('Qty') }}</th>
                                                <th class="text-right">{{ __('Total') }}</th>
                                                <th class="text-center">{{ __('Status') }}</th>
                                                <th>{{ __('Ordered At') }}</th>
                                                <th class="text-right">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function() {
            const filterFields = $('.filter-field');
            const filtersCollapse = $('#filtersCollapse');
            const filterToggleBtn = $('#toggleFilters');
            const tableContainer = $('#table-container');
            const overlayId = 'orders-loading-overlay';
            const overlaySelector = `#${overlayId}`;
            const defaultLoadingMessage = @json(__('Searching...'));
            const ordersTableElement = $('#ordersTable');
            const filterTypeSelect = $('#filterType');
            const emptyStateTemplate = `
                <div class="datatable-empty-state">
                    <i class="fas fa-inbox datatable-empty-state-icon"></i>
                    <h5>{{ __('No orders found') }}</h5>
                    <p>{{ __('Try adjusting your filters or create a new order to get started.') }}</p>
                </div>
            `;

            filtersCollapse.collapse({
                toggle: false
            });

            if (filtersCollapse.hasClass('show')) {
                filterToggleBtn.addClass('active btn-secondary').removeClass('btn-outline-secondary');
            }

            ordersTableElement.on('preXhr.dt', function() {
                showLoadingOverlay();
            });

            ordersTableElement.on('xhr.dt error.dt', function() {
                hideLoadingOverlay();
            });

            showLoadingOverlay();

            const ordersTable = ordersTableElement.DataTable({
                processing: false,
                serverSide: false,
                searching: false,
                lengthChange: false,
                pageLength: 10,
                ajax: {
                    url: "{{ route('order.data') }}",
                    data: function(d) {
                        $('#orderFilters').serializeArray().forEach(function(item) {
                            d[item.name] = item.value;
                        });
                    }
                },
                order: [
                    [6, 'desc']
                ],
                columns: [{
                        data: 'order_number',
                        name: 'order_number'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'product_name',
                        name: 'product_name'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity',
                        className: 'text-center'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                        render: function(data, type) {
                            if (type === 'display' || type === 'filter') {
                                return '$' + data;
                            }
                            return data ? data.replace(/[^0-9.]+/g, '') : data;
                        },
                        className: 'text-right'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            return type === 'display' ? row.status_badge : data;
                        },
                        className: 'text-center'
                    },
                    {
                        data: 'ordered_at',
                        name: 'ordered_at'
                    },
                    {
                        data: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-right'
                    },
                ],
                language: {
                    emptyTable: emptyStateTemplate,
                    info: "{{ __('Showing _START_ to _END_ of _TOTAL_ entries') }}",
                    infoEmpty: "{{ __('Showing 0 entries') }}",
                    paginate: {
                        previous: "{{ __('Previous') }}",
                        next: "{{ __('Next') }}"
                    }
                },
                autoWidth: false,
                dom: 'rt<"row align-items-center mt-3"<"col-sm-6 text-muted"i><"col-sm-6 text-sm-right"p>>'
            });

            function reloadOrders() {
                showLoadingOverlay();
                ordersTable.ajax.reload();
            }

            function updateFilterFields(selectedValue) {
                filterFields.addClass('d-none');
                if (selectedValue) {
                    filterFields.filter(`[data-filter-target="${selectedValue}"]`).removeClass('d-none');
                }
            }

            updateFilterFields(filterTypeSelect.val());

            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: true
            });

            $('#dateRange').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'MM/DD/YYYY',
                    cancelLabel: '{{ __('Clear') }}'
                }
            });

            $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                reloadOrders();
            });

            $('#dateRange').on('cancel.daterangepicker', function() {
                $(this).val('');
                reloadOrders();
            });

            filterTypeSelect.on('change', function() {
                updateFilterFields($(this).val());
                reloadOrders();
            });

            $('#orderFilters').on('change', 'input:not(#dateRange), select:not(#dateRange)', function() {
                reloadOrders();
            });

            $('#exportOrders').on('click', function() {
                const query = $('#orderFilters').serialize();
                const url = "{{ route('order.export') }}" + (query ? '?' + query : '');
                window.location.href = url;
            });

            function hasActiveFilters() {
                let hasValue = false;
                $('#orderFilters').serializeArray().forEach(function(item) {
                    if (item.value) {
                        hasValue = true;
                    }
                });
                return hasValue;
            }

            filterToggleBtn.on('click', function() {
                filtersCollapse.collapse('toggle');
            });

            filtersCollapse.on('shown.bs.collapse', function() {
                filterToggleBtn.addClass('active btn-secondary').removeClass('btn-outline-secondary');
            });

            filtersCollapse.on('hidden.bs.collapse', function() {
                filterToggleBtn.removeClass('active btn-secondary').addClass('btn-outline-secondary');
            });

            if (hasActiveFilters()) {
                filtersCollapse.collapse('show');
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.btn-delete-order', function(e) {
                e.preventDefault();
                const deleteUrl = $(this).data('action');

                const Confirmation = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success mr-2',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                Confirmation.fire({
                    title: '{{ __('Are you sure?') }}',
                    text: '{{ __("You won't be able to revert this!") }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('Yes, delete it!') }}',
                    cancelButtonText: '{{ __('No, cancel!') }}',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'POST',
                            data: {
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                reloadOrders();
                                toastr.success(response.message || '{{ __('Order deleted successfully.') }}');
                            },
                            error: function() {
                                toastr.error('{{ __('Unable to delete order right now.') }}');
                            }
                        });
                    }
                });
            });

            function showLoadingOverlay(message = defaultLoadingMessage) {
                if (!tableContainer.length || $(overlaySelector).length) {
                    return;
                }

                tableContainer.append(`
                    <div class="loading-overlay" id="${overlayId}">
                        <div class="loading-content">
                            <div class="loading-spinner">
                                <i class="fas fa-circle-notch"></i>
                            </div>
                            <div>${message}</div>
                        </div>
                    </div>
                `);
            }

            function hideLoadingOverlay() {
                $(overlaySelector).remove();
            }
        });
    </script>
@endpush
