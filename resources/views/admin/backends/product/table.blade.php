<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Image') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Price') }}</th>
                <th>{{ __('Stock') }}</th>
                <th>{{ __('Category') }}</th>
                <th>{{ __('Brand') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Created') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @if ($products->count() > 0)
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if ($product->image)
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-thumbnail"
                                    style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                    style="width: 50px; height: 50px; border-radius: 4px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $product->name }}</strong>
                        </td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                        <td>{{ $product->brand->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge badge-{{ $product->status == 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td>{{ $product->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-link btn-sm p-0" type="button"
                                    id="actionDropdown{{ $product->id }}" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fas fa-ellipsis-v text-muted"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right"
                                    aria-labelledby="actionDropdown{{ $product->id }}">
                                    <a class="dropdown-item" href="{{ route('product.show', $product) }}">
                                        <i class="fas fa-eye text-info mr-2"></i> {{ __('View') }}
                                    </a>
                                    @can('product.edit')
                                        <a class="dropdown-item" href="{{ route('product.edit', $product) }}">
                                            <i class="fas fa-pencil-alt text-primary mr-2"></i> {{ __('Edit') }}
                                        </a>
                                    @endcan
                                    @can('product.delete')
                                        <a class="dropdown-item btn-delete" href="#" data-id="{{ $product->id }}"
                                            data-href="{{ route('product.destroy', $product) }}">
                                            <i class="fas fa-trash-alt text-danger mr-2"></i> {{ __('Delete') }}
                                        </a>
                                    @endcan
                                </div>
                            </div>
                            <form action="{{ route('product.destroy', $product) }}" method="POST"
                                class="d-none form-delete-{{ $product->id }}">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10" class="text-center py-4">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('No products found') }}</h5>
                            <p class="text-muted">{{ __('There are no products matching your criteria.') }}</p>
                        </div>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    @if ($products->count() > 0)
        <div class="row align-items-center">
            <div class="col-sm-6 pl-4 my-2">
                {{ __('Showing') }} {{ $products->firstItem() }} {{ __('to') }} {{ $products->lastItem() }}
                {{ __('of') }} {{ $products->total() }} {{ __('entries') }}
            </div>
            <div class="col-sm-6 d-flex justify-content-end pr-4 my-2">
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0">
                        <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link"
                                href="{{ $products->previousPageUrl() ?? '#' }}">{{ __('Previous') }}</a>
                        </li>
                        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $products->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach
                        <li class="page-item {{ !$products->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $products->nextPageUrl() ?? '#' }}">{{ __('Next') }}</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    @endif
</div>
