<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Logo') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Slug') }}</th>
                <th>{{ __('Description') }}</th>
                <th>{{ __('Created') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @if ($brands->count() > 0)
                @foreach ($brands as $brand)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if ($brand->logo)
                                <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="img-thumbnail"
                                    style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px; border-radius: 4px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $brand->name }}</strong>
                        </td>
                        <td class="text-muted">{{ $brand->slug }}</td>
                        <td>
                            {{ $brand->description ? \Illuminate\Support\Str::limit($brand->description, 50) : 'No description' }}
                        </td>
                        <td>{{ $brand->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-link btn-sm p-0" type="button"
                                    id="actionDropdown{{ $brand->id }}" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fas fa-ellipsis-v text-muted"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right"
                                    aria-labelledby="actionDropdown{{ $brand->id }}">
                                    <a class="dropdown-item" href="{{ route('brand.show', $brand) }}">
                                        <i class="fas fa-eye text-info mr-2"></i> {{ __('View') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('brand.edit', $brand) }}">
                                        <i class="fas fa-pencil-alt text-primary mr-2"></i> {{ __('Edit') }}
                                    </a>
                                    <a class="dropdown-item btn-delete" href="#" data-id="{{ $brand->id }}"
                                        data-href="{{ route('brand.destroy', $brand) }}">
                                        <i class="fas fa-trash-alt text-danger mr-2"></i> {{ __('Delete') }}
                                    </a>
                                </div>
                            </div>
                            <form action="{{ route('brand.destroy', $brand) }}" method="POST"
                                class="d-none form-delete-{{ $brand->id }}">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="d-flex flex-column align-items-center">
                            {{-- <i class="fas fa-certificate fa-3x text-muted mb-3"></i> --}}
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('No brands found') }}</h5>
                            <p class="text-muted">{{ __('There are no brands matching your criteria.') }}</p>
                        </div>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    @if ($brands->count() > 0)
        <div class="row align-items-center">
            <div class="col-sm-6 pl-4 my-2">
                {{ __('Showing') }} {{ $brands->firstItem() }} {{ __('to') }} {{ $brands->lastItem() }}
                {{ __('of') }} {{ $brands->total() }} {{ __('entries') }}
            </div>
            <div class="col-sm-6 d-flex justify-content-end pr-4 my-2">
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0">
                        <li class="page-item {{ $brands->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link"
                                href="{{ $brands->previousPageUrl() ?? '#' }}">{{ __('Previous') }}</a>
                        </li>
                        @foreach ($brands->getUrlRange(1, $brands->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $brands->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach
                        <li class="page-item {{ !$brands->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $brands->nextPageUrl() ?? '#' }}">{{ __('Next') }}</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    @endif
</div>
