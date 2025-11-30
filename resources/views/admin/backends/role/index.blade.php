    @extends('admin.layouts.app')
    @push('css')
    @endpush
    @section('contents')
        <section class="content-header">
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-6 col-xs-6 col-sm-6">
                                        <h3 class="card-title">{{ __('Role Management List') }}</h3>
                                    </div>
                                    <div class="col-6 col-xs-6 col-sm-6 text-right">
                                        @can('role.create')
                                            <a class="btn btn-primary" href="{{ route('role.create') }}">
                                                <i class="fa fa-plus-circle mr-1"></i>
                                                {{ __('Add New') }}
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            @include('admin.backends.role.table')
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
                    title: "{{ __('Are you sure?') }}",
                    text: "{{ __("You won't be able to revert this!") }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: "{{ __('Yes, delete it!') }}",
                    cancelButtonText: "{{ __('No, cancel!') }}",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {

                        var deleteForm = $(`.form-delete-${$(this).data('id')}`);
                        var formData = deleteForm.serialize();

                        $.ajax({
                            type: "DELETE", // Changed from POST to DELETE
                            url: deleteForm.attr('action'), // Use the action URL from the form
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
                                toastr.error('{{ __('Something went wrong. Please try again!') }}');
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
