@extends('admin.layouts.app')

@section('contents')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Author Details') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('author.index') }}">{{ __('Authors') }}</a></li>
                        <li class="breadcrumb-item active">{{ $author->name }}</li>
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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">{{ $author->name }}</h3>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">{{ __('Full Name') }}</dt>
                                <dd class="col-sm-8">{{ $author->name }}</dd>

                                <dt class="col-sm-4">{{ __('Email') }}</dt>
                                <dd class="col-sm-8">
                                    <a href="mailto:{{ $author->email }}">{{ $author->email }}</a>
                                </dd>

                                <dt class="col-sm-4">{{ __('Phone') }}</dt>
                                <dd class="col-sm-8">{{ $author->phone ?? __('N/A') }}</dd>

                                <dt class="col-sm-4">{{ __('Website') }}</dt>
                                <dd class="col-sm-8">
                                    @if ($author->website)
                                        <a href="{{ $author->website }}" target="_blank" rel="noopener">
                                            {{ $author->website }}
                                        </a>
                                    @else
                                        {{ __('N/A') }}
                                    @endif
                                </dd>

                                <dt class="col-sm-4">{{ __('Created At') }}</dt>
                                <dd class="col-sm-8">{{ $author->created_at->format('M d, Y \\a\\t H:i') }}</dd>

                                <dt class="col-sm-4">{{ __('Last Updated') }}</dt>
                                <dd class="col-sm-8">{{ $author->updated_at->format('M d, Y \\a\\t H:i') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Actions') }}</h3>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('author.edit', $author->id) }}" class="btn btn-primary btn-block mb-2">
                                <i class="fas fa-edit mr-1"></i> {{ __('Edit Author') }}
                            </a>
                            <form action="{{ route('author.destroy', $author->id) }}" method="POST" id="deleteForm">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-block btn-delete">
                                    <i class="fas fa-trash mr-1"></i> {{ __('Delete Author') }}
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

            const confirmation = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success mr-2',
                    cancelButton: 'btn btn-danger mr-2'
                },
                buttonsStyling: false
            });

            confirmation.fire({
                title: "{{ __('Are you sure?') }}",
                text: "{{ __("You won't be able to revert this!") }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "{{ __('Yes, delete it!') }}",
                cancelButtonText: "{{ __('No, cancel!') }}",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#deleteForm').submit();
                }
            });
        });
    </script>
@endpush
