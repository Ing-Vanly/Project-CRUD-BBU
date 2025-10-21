<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Slug') }}</th>
                <th>{{ __('Author') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Published') }}</th>
                <th>{{ __('Created') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            {{-- @if ($posts->count() > 0)
                @foreach ($posts as $post)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ \Illuminate\Support\Str::limit($post->title, 30) }}</strong>
                            <div class="text-muted small">
                                {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 50) }}</div>
                        </td>
                        <td class="text-muted">{{ $post->slug }}</td>
                        <td>{{ $post->user ? $post->user->name : 'N/A' }}</td>
                        <td>
                            @if ($post->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <label class="switch mr-2">
                                    <input type="checkbox" class="toggle-published" data-id="{{ $post->id }}"
                                        {{ $post->is_published ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                                <span class="badge {{ $post->is_published ? 'badge-primary' : 'badge-warning' }}">
                                    {{ $post->is_published ? 'Published' : 'Unpublished' }}
                                </span>
                            </div>
                        </td>
                        <td>{{ $post->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-link btn-sm p-0" type="button"
                                    id="actionDropdown{{ $post->id }}" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fas fa-ellipsis-v text-muted"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right"
                                    aria-labelledby="actionDropdown{{ $post->id }}">
                                    <a class="dropdown-item" href="{{ route('post.show', $post->id) }}">
                                        <i class="fas fa-eye text-info mr-2"></i> {{ __('View') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('post.edit', $post->id) }}">
                                        <i class="fas fa-pencil-alt text-primary mr-2"></i> {{ __('Edit') }}
                                    </a>
                                    <a class="dropdown-item btn-delete" href="#" data-id="{{ $post->id }}"
                                        data-href="{{ route('post.destroy', $post->id) }}">
                                        <i class="fas fa-trash-alt text-danger mr-2"></i> {{ __('Delete') }}
                                    </a>
                                </div>
                            </div>
                            <form action="{{ route('post.destroy', $post->id) }}" method="POST"
                                class="d-none form-delete-{{ $post->id }}">
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
            @endif --}}
        </tbody>
    </table>
    {{-- @if ($posts->count() > 0)
        <div class="row align-items-center">
            <div class="col-sm-6 pl-4 my-2">
                {{ __('Showing') }} {{ $posts->firstItem() }} {{ __('to') }} {{ $posts->lastItem() }}
                {{ __('of') }} {{ $posts->total() }} {{ __('entries') }}
            </div>
            <div class="col-sm-6 d-flex justify-content-end pr-4 my-2">
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0">
                        <li class="page-item {{ $posts->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $posts->previousPageUrl() ?? '#' }}">{{ __('Previous') }}</a>
                        </li>
                        @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $posts->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach
                        <li class="page-item {{ !$posts->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $posts->nextPageUrl() ?? '#' }}">{{ __('Next') }}</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    @endif --}}
</div>
