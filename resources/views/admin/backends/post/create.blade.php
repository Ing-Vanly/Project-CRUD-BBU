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

        .content-editor {
            min-height: 200px;
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
                            <form class="form-material form-horizontal" action="{{ route('post.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title">{{ __('Post Title') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="title" name="title"
                                                class="form-control @error('title') is-invalid @enderror"
                                                placeholder="{{ __('Enter post title') }}" value="{{ old('title') }}"
                                                onkeyup="generateSlug()">
                                            @error('title')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="slug">{{ __('Slug') }}</label>
                                            <input type="text" id="slug" name="slug"
                                                class="form-control @error('slug') is-invalid @enderror"
                                                placeholder="{{ __('Auto-generated from title or enter custom') }}"
                                                value="{{ old('slug') }}">
                                            @error('slug')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            {{-- <small class="form-text text-muted">{{ __('Leave empty to auto-generate from title') }}</small> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="content">{{ __('Content') }} <span
                                                class="text-danger">*</span></label>
                                        <textarea id="content" name="content" class="form-control content-editor @error('content') is-invalid @enderror"
                                            placeholder="{{ __('Write your post content here...') }}" rows="8">{{ old('content') }}</textarea>
                                        @error('content')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>​
                                </div>​
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">{{ __('Post Settings') }}</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <label for="is_active">{{ __('Active Status') }}</label>
                                                    <label class="switch">
                                                        <input type="checkbox" id="is_active" name="is_active"
                                                            value="1" {{ old('is_active') ? 'checked' : 'checked' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                                <small
                                                    class="form-text text-muted">{{ __('Enable/disable this post') }}</small>
                                            </div>
                                            <div class="form-group">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <label for="is_published">{{ __('Published Status') }}</label>
                                                    <label class="switch">
                                                        <input type="checkbox" id="is_published" name="is_published"
                                                            value="1" {{ old('is_published') ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                                <small
                                                    class="form-text text-muted">{{ __('Publish this post publicly') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group text-right">
                                        <a href="{{ route('post.index') }}" class="btn outline btn-danger">
                                            {{ __('Cancel') }}
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Create Post') }}
                                        </button>
                                    </div>
                                </div>
                        </div>
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
        function generateSlug() {
            const title = document.getElementById('title').value;
            const slug = title.toLowerCase()
                .replace(/[^a-z0-9 -]/g, '') // Remove invalid chars
                .replace(/\s+/g, '-') // Replace spaces with -
                .replace(/-+/g, '-') // Replace multiple - with single -
                .trim('-'); // Trim - from start and end

            document.getElementById('slug').value = slug;
        }

        // Initialize Summernote editor for better content editing
        $(document).ready(function() {
            $('#content').summernote({
                height: 300,
                placeholder: '{{ __('Write your post content here...') }}',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endpush
