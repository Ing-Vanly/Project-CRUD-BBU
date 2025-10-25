<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th>#</th>
                <th>{{ __('Branding') }}</th>
                <th>{{ __('Contact') }}</th>
                <th>{{ __('Address') }}</th>
                <th>{{ __('Footer Text') }}</th>
                <th>{{ __('Created') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($locations as $location)
                <tr>
                    <td>{{ $loop->iteration + ($locations->perPage() * ($locations->currentPage() - 1)) }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="mr-3" style="width: 54px; height: 54px;">
                                @if ($location->logo)
                                    <img src="{{ asset($location->logo) }}" alt="{{ $location->name }}"
                                        class="img-thumbnail" style="width: 54px; height: 54px; object-fit: contain;">
                                @else
                                    <div class="bg-light border d-flex align-items-center justify-content-center"
                                        style="width: 54px; height: 54px; border-radius: 6px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <strong>{{ $location->name }}</strong>
                                @if ($location->website)
                                    <div class="small">
                                        <a href="{{ $location->website }}" target="_blank" rel="noopener">
                                            {{ $location->website }}
                                        </a>
                                    </div>
                                @endif
                                @if ($location->favicon)
                                    <img src="{{ asset($location->favicon) }}" alt="favicon" class="mt-1"
                                        style="width: 18px; height: 18px; object-fit: contain;">
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="small">
                            <i class="fas fa-envelope text-muted mr-2"></i>{{ $location->email }}
                        </div>
                        <div class="small text-muted">
                            <i class="fas fa-phone mr-2"></i>{{ $location->phone ?: __('Not provided') }}
                        </div>
                    </td>
                    <td>{{ \Illuminate\Support\Str::limit($location->address, 60) }}</td>
                    <td>
                        {{ $location->footer_text ? \Illuminate\Support\Str::limit($location->footer_text, 60) : __('Not set') }}
                    </td>
                    <td>{{ $location->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-link btn-sm p-0" type="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v text-muted"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('business-location.show', $location) }}">
                                    <i class="fas fa-eye text-info mr-2"></i> {{ __('View') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('business-location.edit', $location) }}">
                                    <i class="fas fa-pen text-primary mr-2"></i> {{ __('Edit') }}
                                </a>
                                <a class="dropdown-item btn-delete-location" href="#" data-id="{{ $location->id }}">
                                    <i class="fas fa-trash text-danger mr-2"></i> {{ __('Delete') }}
                                </a>
                            </div>
                        </div>
                        <form action="{{ route('business-location.destroy', $location) }}" method="POST"
                            class="d-none form-delete-{{ $location->id }}">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('No business locations found') }}</h5>
                            <p class="text-muted mb-0">{{ __('Start by adding your first business location.') }}</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($locations->count())
        <div class="row align-items-center px-3 pb-3">
            <div class="col-md-6 text-muted">
                {{ __('Showing') }} {{ $locations->firstItem() }} {{ __('to') }} {{ $locations->lastItem() }}
                {{ __('of') }} {{ $locations->total() }} {{ __('entries') }}
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                {{ $locations->appends(request()->only(['search', 'created']))->onEachSide(1)->links() }}
            </div>
        </div>
    @endif
</div>
