@php
    $modules = $permissionModules ?? [];
    $selectedPermissions = old('permissions', $selectedPermissions ?? []);
@endphp

<div class="permission-toolbar d-flex flex-wrap align-items-center mb-3">
    <span class="font-weight-bold mr-3">{{ __('Permissions') }}</span>
    <div class="btn-group btn-group-sm" role="group">
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

<div class="permission-grid">
    @forelse ($modules as $moduleKey => $module)
        <div class="permission-card" data-module="{{ $moduleKey }}">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h5 class="mb-1 text-uppercase text-muted small">{{ __($module['label'] ?? ucfirst($moduleKey)) }}</h5>
                    <p class="mb-0 text-secondary small">{{ __('Manage :module permissions', ['module' => __($module['label'] ?? ucfirst($moduleKey))]) }}</p>
                </div>
                <button class="btn btn-link btn-sm text-primary module-toggle" type="button"
                    data-module-toggle="{{ $moduleKey }}">
                    {{ __('Toggle module') }}
                </button>
            </div>
            <div class="row">
                @foreach ($module['actions'] ?? [] as $actionKey => $actionLabel)
                    @php
                        $permissionName = "{$moduleKey}.{$actionKey}";
                    @endphp
                    <div class="col-md-3 col-sm-4 mb-2">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input permission-checkbox"
                                id="permission-{{ $permissionName }}" name="permissions[]"
                                data-module="{{ $moduleKey }}" value="{{ $permissionName }}"
                                {{ in_array($permissionName, $selectedPermissions, true) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="permission-{{ $permissionName }}">
                                {{ __($actionLabel) }}
                            </label>
                        </div>
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
            .permission-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 1rem;
            }

            .permission-card {
                border: 1px solid #e2e8f0;
                border-radius: 0.5rem;
                padding: 1.25rem;
                background-color: #fff;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            }

            .permission-card h5 {
                letter-spacing: 0.08em;
                font-size: 0.75rem;
            }

            .permission-toolbar .btn + .btn {
                margin-left: 0.25rem;
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

                const toggleModule = (moduleKey, shouldCheck) => {
                    document.querySelectorAll(`.permission-checkbox[data-module="${moduleKey}"]`).forEach(function(
                        checkbox) {
                        checkbox.checked = shouldCheck;
                    });
                };

                const anyUnchecked = (moduleKey) => {
                    return Array.from(document.querySelectorAll(`.permission-checkbox[data-module="${moduleKey}"]`))
                        .some((checkbox) => !checkbox.checked);
                };

                if (selectAllBtn) {
                    selectAllBtn.addEventListener('click', function() {
                        document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
                            checkbox.checked = true;
                        });
                    });
                }

                if (clearAllBtn) {
                    clearAllBtn.addEventListener('click', function() {
                        document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
                            checkbox.checked = false;
                        });
                    });
                }

                document.querySelectorAll('.module-toggle').forEach(function(button) {
                    button.addEventListener('click', function() {
                        const moduleKey = this.getAttribute('data-module-toggle');
                        const shouldCheck = anyUnchecked(moduleKey);
                        toggleModule(moduleKey, shouldCheck);
                    });
                });
            });
        </script>
    @endpush
@endonce
