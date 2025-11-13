<div class="card-body p-0 table-wrapper">
    <table class="table table-hover">
        <thead class="text-uppercase">
            <tr>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Phone') }}</th>
                <th>{{ __('Website') }}</th>
                <th>{{ __('Created') }}</th>
                <th>{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($authors as $author)
                <tr>
                    <td>{{ $loop->iteration + ($authors->currentPage() - 1) * $authors->perPage() }}</td>
                    <td>
                        <strong>{{ $author->name }}</strong>
                    </td>
                    <td>{{ $author->email }}</td>
                    <td>{{ $author->phone ?? __('N/A') }}</td>
                    <td>
                        @if ($author->website)
                            <a href="{{ $author->website }}" target="_blank" rel="noopener">
                                {{ \Illuminate\Support\Str::limit($author->website, 40) }}
                            </a>
                        @else
                            <span class="text-muted">{{ __('N/A') }}</span>
                        @endif
                    </td>
                    <td>{{ $author->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-link btn-sm p-0" type="button"
                                id="actionDropdown{{ $author->id }}" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fas fa-ellipsis-v text-muted"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right"
                                aria-labelledby="actionDropdown{{ $author->id }}">
                                <a class="dropdown-item" href="{{ route('author.show', $author->id) }}">
                                    <i class="fas fa-eye text-info mr-2"></i> {{ __('View') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('author.edit', $author->id) }}">
                                    <i class="fas fa-pencil-alt text-primary mr-2"></i> {{ __('Edit') }}
                                </a>
                                <a class="dropdown-item btn-delete" href="#" data-id="{{ $author->id }}">
                                    <i class="fas fa-trash-alt text-danger mr-2"></i> {{ __('Delete') }}
                                </a>
                            </div>
                        </div>
                        <form action="{{ route('author.destroy', $author->id) }}" method="POST"
                            class="d-none form-delete-{{ $author->id }}">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted mb-1">{{ __('No authors found') }}</h5>
                            <p class="text-muted mb-0">{{ __('Try adjusting your search.') }}</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($authors->count() > 0)
        <div class="row align-items-center px-3 pb-3">
            <div class="col-sm-6 my-2">
                {{ __('Showing') }} {{ $authors->firstItem() }} {{ __('to') }} {{ $authors->lastItem() }}
                {{ __('of') }} {{ $authors->total() }} {{ __('entries') }}
            </div>
            <div class="col-sm-6 d-flex justify-content-end my-2">
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0">
                        <li class="page-item {{ $authors->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $authors->previousPageUrl() ?? '#' }}">{{ __('Previous') }}</a>
                        </li>
                        @foreach ($authors->getUrlRange(1, $authors->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $authors->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach
                        <li class="page-item {{ !$authors->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $authors->nextPageUrl() ?? '#' }}">{{ __('Next') }}</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    @endif
</div>
