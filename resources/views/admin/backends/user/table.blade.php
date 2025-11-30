<div class="card-body p-0 table-wrapper">
    <table class="table table-hover text-nowrap mb-0">
        <thead>
            <tr>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Image') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Role') }}</th>
                <th>{{ __('Created') }}</th>
                <th width="10%">{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $key => $user)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ getImagePath($user->image ?? null, 'users') }}"
                                alt="{{ $user->name }}" class="rounded-circle border" width="45" height="45"
                                style="object-fit: cover;">
                        </div>
                    </td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if ($user->roles->isNotEmpty())
                            <span class="badge badge-primary">{{ $user->roles->first()->name }}</span>
                        @else
                            <span class="badge badge-secondary">{{ __('No Role') }}</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-link btn-sm p-0" type="button"
                                id="actionDropdownUser{{ $user->id }}" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fas fa-ellipsis-v text-muted"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right"
                                aria-labelledby="actionDropdownUser{{ $user->id }}">
                                <a href="{{ route('user.edit', $user->id) }}" class="dropdown-item">
                                    <i class="fas fa-pencil-alt text-primary mr-2"></i> {{ __('Edit') }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <form class="form-delete-{{ $user->id }}"
                                    action="{{ route('user.destroy', $user->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" data-id="{{ $user->id }}"
                                        class="dropdown-item text-danger btn-delete">
                                        <i class="fas fa-trash-alt mr-2"></i> {{ __('Delete') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">{{ __('No data found') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="row align-items-center px-4 py-3 border-top">
        <div class="col-sm-6 my-2">
            {{ __('Showing') }} {{ $users->firstItem() ?? 0 }} {{ __('to') }}
            {{ $users->lastItem() ?? 0 }} {{ __('of') }} {{ $users->total() }}
            {{ __('entries') }}
        </div>
        <div class="col-sm-6 d-flex justify-content-end my-2">
            <nav aria-label="User pagination">
                <ul class="pagination mb-0">
                    <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $users->previousPageUrl() ?? '#' }}" tabindex="-1">
                            {{ __('Previous') }}
                        </a>
                    </li>

                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $users->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    <li class="page-item {{ !$users->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $users->nextPageUrl() ?? '#' }}">
                            {{ __('Next') }}
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
