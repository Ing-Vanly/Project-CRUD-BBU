<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th>{{ __('ID') }} </th>
                <th>{{ __('Name') }} </th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-capitalize">{{ $role->name }}</td>
                    <td>
                        @canany(['role.edit', 'role.delete'])
                            <div class="dropdown">
                                <button class="btn btn-link btn-sm p-0" type="button"
                                    id="actionDropdownRole{{ $role->id }}" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fas fa-ellipsis-v text-muted"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right"
                                    aria-labelledby="actionDropdownRole{{ $role->id }}">
                                    @can('role.edit')
                                        <a href="{{ route('role.edit', $role->id) }}" class="dropdown-item">
                                            <i class="fas fa-pencil-alt text-primary mr-2"></i> {{ __('Edit') }}
                                        </a>
                                    @endcan
                                    @can('role.delete')
                                        @can('role.edit')
                                            <div class="dropdown-divider"></div>
                                        @endcan
                                        <form action="{{ route('role.destroy', $role->id) }}" method="POST"
                                            class="form-delete-{{ $role->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="dropdown-item text-danger btn-delete"
                                                data-id="{{ $role->id }}" data-href="{{ route('role.destroy', $role->id) }}">
                                                <i class="fas fa-trash-alt mr-2"></i> {{ __('Delete') }}
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        @else
                            <span class="text-muted">{{ __('No actions available') }}</span>
                        @endcanany
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row align-items-center">
        <div class="col-sm-6 pl-4 my-2">
            {{ __('Showing') }} {{ $roles->firstItem() }} {{ __('to') }} {{ $roles->lastItem() }}
            {{ __('of') }} {{ $roles->total() }} {{ __('entries') }}
        </div>
        <div class="col-sm-6 d-flex justify-content-end pr-4 my-2">
            <nav aria-label="Page navigation">
                <ul class="pagination mb-0">
                    {{-- Previous Page Link --}}
                    <li class="page-item {{ $roles->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $roles->previousPageUrl() ?? '#' }}">{{ __('Previous') }}</a>
                    </li>

                    {{-- Pagination Elements --}}
                    @foreach ($roles->getUrlRange(1, $roles->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $roles->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    {{-- Next Page Link --}}
                    <li class="page-item {{ !$roles->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $roles->nextPageUrl() ?? '#' }}">{{ __('Next') }}</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
