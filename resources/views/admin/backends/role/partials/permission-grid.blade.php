@php
    $modules = $permissionModules ?? [];
    $selectedPermissions = old('permissions', $selectedPermissions ?? []);
@endphp

<div class="permission-toolbar d-flex flex-wrap align-items-center justify-content-between mb-3">
    <span class="font-weight-bold">{{ __('Permissions') }}</span>
    <div class="btn-group btn-group-sm ml-auto" role="group">
        <button type="button" class="btn btn-outline-primary" id="select-all-permissions">
            <i class="fas fa-check-double mr-1"></i> {{ __('Select all') }}
        </button>
        <button type="button" class="btn btn-outline-secondary" id="clear-all-permissions">
            <i class="fas fa-ban mr-1"></i> {{ __('Clear all') }}
        </button>
    </div>
</div>

@error('permissions')
    <div class="alert alert-danger py-2">{{ $message }}</div>
@enderror

<div class="permission-list">
    @forelse ($modules as $moduleKey => $module)
        <div class="permission-row" data-module="{{ $moduleKey }}">
            <div class="permission-row-heading">
                <div>
                    <p class="mb-1 font-weight-bold">{{ __($module['label'] ?? ucfirst($moduleKey)) }}</p>
                    <small
                        class="text-muted">{{ __('Manage :module permissions', ['module' => __($module['label'] ?? ucfirst($moduleKey))]) }}</small>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input module-checkbox" id="module-{{ $moduleKey }}"
                        data-module="{{ $moduleKey }}">
                    <label class="custom-control-label text-sm" for="module-{{ $moduleKey }}">
                        {{ __('Select all') }}
                    </label>
                </div>
            </div>
            <div class="permission-row-actions">
                @foreach ($module['actions'] ?? [] as $actionKey => $actionLabel)
                    @php
                        $permissionName = "{$moduleKey}.{$actionKey}";
                    @endphp
                    <div class="custom-control custom-checkbox mr-4 mb-2">
                        <input type="checkbox" class="custom-control-input permission-checkbox"
                            id="permission-{{ $permissionName }}" name="permissions[]"
                            data-module="{{ $moduleKey }}" value="{{ $permissionName }}"
                            {{ in_array($permissionName, $selectedPermissions, true) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="permission-{{ $permissionName }}">
                            {{ __($actionLabel) }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <p class="text-muted">{{ __('No permissions configured yet.') }}</p>
    @endforelse
</div>

@once
    @push('css')
        <style>
            .permission-toolbar .btn+.btn {
                margin-left: 0.25rem;
            }

            .permission-list {
                border: 1px solid #e2e8f0;
                border-radius: 0.5rem;
                background-color: #fff;
            }

            .permission-row+.permission-row {
                border-top: 1px solid #e2e8f0;
            }

            .permission-row {
                padding: 1rem 1.25rem;
            }

            .permission-row-heading {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 0.75rem;
            }

            .permission-row-actions {
                display: flex;
                flex-wrap: wrap;
            }
        </style>
    @endpush
@endonce

@once
    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectAllBtn = document.getElementById('select-all-permissions');
                const clearAllBtn = document.getElementById('clear-all-permissions');

                const updateModuleState = (moduleKey) => {
                    const moduleCheckbox = document.querySelector(`.module-checkbox[data-module="${moduleKey}"]`);
                    if (!moduleCheckbox) {
                        return;
                    }
                    const modulePermissions = document.querySelectorAll(
                        `.permission-checkbox[data-module="${moduleKey}"]`);
                    const total = modulePermissions.length;
                    const checkedCount = Array.from(modulePermissions).filter((checkbox) => checkbox.checked)
                    .length;

                    moduleCheckbox.indeterminate = checkedCount > 0 && checkedCount < total;
                    moduleCheckbox.checked = total > 0 && checkedCount === total;
                };

                const refreshAllModuleStates = () => {
                    document.querySelectorAll('.module-checkbox').forEach(function(moduleCheckbox) {
                        updateModuleState(moduleCheckbox.getAttribute('data-module'));
                    });
                };

                if (selectAllBtn) {
                    selectAllBtn.addEventListener('click', function() {
                        document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
                            checkbox.checked = true;
                        });
                        refreshAllModuleStates();
                    });
                }

                if (clearAllBtn) {
                    clearAllBtn.addEventListener('click', function() {
                        document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
                            checkbox.checked = false;
                        });
                        refreshAllModuleStates();
                    });
                }

                document.querySelectorAll('.module-checkbox').forEach(function(moduleCheckbox) {
                    moduleCheckbox.addEventListener('change', function() {
                        const moduleKey = this.getAttribute('data-module');
                        const shouldCheck = this.checked;

                        document.querySelectorAll(`.permission-checkbox[data-module="${moduleKey}"]`)
                            .forEach(function(checkbox) {
                                checkbox.checked = shouldCheck;
                            });

                        updateModuleState(moduleKey);
                    });

                    updateModuleState(moduleCheckbox.getAttribute('data-module'));
                });

                document.querySelectorAll('.permission-checkbox').forEach(function(permissionCheckbox) {
                    permissionCheckbox.addEventListener('change', function() {
                        updateModuleState(this.getAttribute('data-module'));
                    });
                });
            });
        </script>
    @endpush
@endonce
