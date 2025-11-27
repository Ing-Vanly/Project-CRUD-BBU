@extends('admin.layouts.app')
@section('contents')
    <section class="content-header">
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('role.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">{{ __('Role Name') }}</label>
                                    <input type="text" id="name" name="name"
                                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                        placeholder="{{ __('Enter role name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @include('admin.backends.role.partials.permission-grid', [
                                    'permissionModules' => $permissionModules,
                                    'selectedPermissions' => $selectedPermissions,
                                ])
                                <div class="d-flex justify-content-end align-items-center mt-4">
                                    <a href="{{ route('role.index') }}" class="btn btn-outline-danger mr-2">
                                        {{ __('Cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i> {{ __('Save Role') }}
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
