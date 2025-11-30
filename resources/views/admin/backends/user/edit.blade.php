@extends('admin.layouts.app')
@push('css')
@endpush

@include('admin.components.media-upload-styles')
@include('admin.components.media-preview-script')
@section('contents')
    <section class="content-header">
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Edit User') }}: {{ $user->name }}</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ __('Name') }}</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name" value="{{ old('name', $user->name) }}"
                                                placeholder="{{ __('Enter user name') }}" required>
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">{{ __('Email') }}</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email" value="{{ old('email', $user->email) }}"
                                                placeholder="{{ __('Enter email address') }}" required>
                                            @error('email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">{{ __('Password') }}</label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror" id="password"
                                                name="password"
                                                placeholder="{{ __('Leave blank to keep current password') }}">
                                            @error('password')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                            <small
                                                class="text-muted">{{ __('Leave blank to keep current password') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation"
                                                placeholder="{{ __('Confirm new password') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role">{{ __('Role') }}</label>
                                            <select class="form-control @error('role') is-invalid @enderror" id="role"
                                                name="role" required>
                                                <option value="">{{ __('Select Role') }}</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}"
                                                        {{ old('role', $user->roles->first()->name ?? '') == $role->name ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="image">{{ __('Profile Image') }}</label>
                                            <input type="file" name="image" id="image"
                                                class="form-control @error('image') is-invalid @enderror"
                                                accept="image/*">
                                            @error('image')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                            <small class="form-text text-muted">{{ __('Leave empty to keep current image. Accepted formats: JPG, PNG, GIF. Max size: 2MB') }}</small>
                                            <div class="media-preview mt-2" id="userImagePreview">
                                                @if ($user->image)
                                                    <img src="{{ getImagePath($user->image, 'users') }}" alt="{{ $user->name }}" class="img-fluid">
                                                @else
                                                    <div class="text-muted text-center">
                                                        <i class="fas fa-user-circle fa-2x mb-2"></i>
                                                        <p class="mb-0">{{ __('Image preview will appear here') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group d-flex justify-content-end">
                                    <div class="ml-auto">
                                        <a href="{{ route('user.index') }}" class="btn btn-outline-danger mr-2">
                                            {{ __('Back') }}
                                        </a>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i>
                                        {{ __('Update User') }}
                                    </button>
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
        attachImagePreview('image', 'userImagePreview', `
            <div class="text-muted text-center">
                <i class="fas fa-user-circle fa-2x mb-2"></i>
                <p class="mb-0">{{ __('Image preview will appear here') }}</p>
            </div>
        `);
    </script>
@endpush
