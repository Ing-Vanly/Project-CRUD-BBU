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
                        <a href="{{ route('role.edit', $role->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-pencil-alt"></i> {{ __('Edit') }}
                        </a>
                        <form action="{{ route('role.destroy', $role->id) }}" method="POST"
                            class="d-inline-block form-delete-{{ $role->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm btn-delete"
                                data-id="{{ $role->id }}" data-href="{{ route('role.destroy', $role->id) }}">
                                <i class="fas fa-trash-alt"></i> {{ __('Delete') }}
                            </button>
                        </form>
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
