<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Slug') }}</th>
                <th>{{ __('Description') }}</th>
                <th>{{ __('Created') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @if ($categories->count() > 0)
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ \Illuminate\Support\Str::limit($category->name, 30) }}</strong>
                        </td>
                        <td class="text-muted">{{ $category->slug }}</td>
                        <td>{{ $category->created_at->format('M d, Y') }}</td>
                        <td>{{ $category->description }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-link btn-sm p-0" type="button"
                                    id="actionDropdown{{ $category->id }}" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fas fa-ellipsis-v text-muted"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right"
                                    aria-labelledby="actionDropdown{{ $category->id }}">
                                    <a class="dropdown-item" href="{{ route('category.show', $category->id) }}">
                                        <i class="fas fa-eye text-info mr-2"></i> {{ __('View') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('category.edit', $category->id) }}">
                                        <i class="fas fa-pencil-alt text-primary mr-2"></i> {{ __('Edit') }}
                                    </a>
                                    <a class="dropdown-item btn-delete" href="#" data-id="{{ $category->id }}"
                                        data-href="{{ route('category.destroy', $category->id) }}">
                                        <i class="fas fa-trash-alt text-danger mr-2"></i> {{ __('Delete') }}
                                    </a>
                                </div>
                            </div>
                            <form action="{{ route('category.destroy', $category->id) }}" method="POST"
                                class="d-none form-delete-{{ $category->id }}">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('No posts found') }}</h5>
                            <p class="text-muted">{{ __('There are no posts matching your criteria.') }}</p>
                        </div>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    @if ($categories->count() > 0)
        <div class="row align-items-center">
            <div class="col-sm-6 pl-4 my-2">
                {{ __('Showing') }} {{ $categories->firstItem() }} {{ __('to') }} {{ $categories->lastItem() }}
                {{ __('of') }} {{ $categories->total() }} {{ __('entries') }}
            </div>
            <div class="col-sm-6 d-flex justify-content-end pr-4 my-2">
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0">
                        <li class="page-item {{ $categories->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $categories->previousPageUrl() ?? '#' }}">{{ __('Previous') }}</a>
                        </li>
                        @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $categories->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach
                        <li class="page-item {{ !$categories->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $categories->nextPageUrl() ?? '#' }}">{{ __('Next') }}</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    @endif
</div>
