@extends('admin.layouts.app')

@push('css')
@endpush

@section('contents')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>{{ __('Role') }}</h3>
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
                                    <h3 class="card-title">{{ __('User role list') }}</h3>
                                </div>
                                <div class="col-6 col-xs-6 col-sm-6">
                                    <a class="btn btn-primary float-right" href="{{ route('role.create') }}">
                                        <i class=" fa fa-plus-circle"></i>
                                        {{ __('Add New') }}
                                    </a>
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
                        type: "DELETE",  // Changed from POST to DELETE
                        url: deleteForm.attr('action'),  // Use the action URL from the form
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
    </script>
@endpush
