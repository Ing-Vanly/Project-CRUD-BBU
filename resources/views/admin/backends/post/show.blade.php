@extends('admin.layouts.app')

@push('css')
    <style>
        .post-content {
            line-height: 1.6;
        }
        .post-meta {
            border-left: 4px solid #007bff;
            padding-left: 15px;
            background-color: #f8f9fa;
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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Post Information') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Post Title') }}</label>
                                        <p class="form-control-plaintext font-weight-bold mb-0">{{ $post->title }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Slug') }}</label>
                                        <p class="form-control-plaintext text-muted mb-0">{{ $post->slug ?? __('N/A') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('Content') }}</label>
                                        <div class="border rounded p-3 post-content">
                                            {!! $post->content !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">{{ __('Post Settings') }}</h3>
                                        </div>
                                        <div class="card-body post-meta">
                                            <div class="form-group mb-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <strong>{{ __('Active Status') }}</strong>
                                                    @if($post->is_active)
                                                        <span class="badge badge-success ml-2">{{ __('Active') }}</span>
                                                    @else
                                                        <span class="badge badge-secondary ml-2">{{ __('Inactive') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group mb-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <strong>{{ __('Published Status') }}</strong>
                                                    @if($post->is_published)
                                                        <span class="badge badge-primary ml-2">{{ __('Published') }}</span>
                                                    @else
                                                        <span class="badge badge-warning ml-2">{{ __('Draft') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group mb-3">
                                                <strong>{{ __('Author:') }}</strong>
                                                <span>{{ $post->user ? $post->user->name : __('Unknown') }}</span>
                                            </div>
                                            <div class="mt-3">
                                                <small class="text-muted d-block">
                                                    <strong>{{ __('Created:') }}</strong> {{ $post->created_at->format('M d, Y \a\t H:i') }}
                                                </small>
                                                <small class="text-muted d-block">
                                                    <strong>{{ __('Last Updated:') }}</strong> {{ $post->updated_at->format('M d, Y \a\t H:i') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group text-right">
                                        <a href="{{ route('post.index') }}" class="btn outline btn-danger">
                                            {{ __('Back to Posts') }}
                                        </a>
                                        <a href="{{ route('post.edit', $post->id) }}" class="btn btn-primary">
                                            {{ __('Edit Post') }}
                                        </a>
                                        <form action="{{ route('post.destroy', $post->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-delete" data-id="{{ $post->id }}">
                                                {{ __('Delete Post') }}
                                            </button>
                                        </form>
                                    </div>
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
