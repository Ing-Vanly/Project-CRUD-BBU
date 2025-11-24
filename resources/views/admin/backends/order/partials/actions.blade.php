<div class="dropdown text-right">
    <button class="btn btn-link btn-sm p-0 text-muted" type="button"
        id="orderActionDropdown{{ $order->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="orderActionDropdown{{ $order->id }}">
        <a class="dropdown-item" href="{{ route('order.show', $order) }}">
            <i class="fas fa-eye text-info mr-2"></i> {{ __('View') }}
        </a>
        <a class="dropdown-item" href="{{ route('order.edit', $order) }}">
            <i class="fas fa-edit text-primary mr-2"></i> {{ __('Edit') }}
        </a>
        <a class="dropdown-item text-danger btn-delete-order" href="#" data-action="{{ route('order.destroy', $order) }}">
            <i class="fas fa-trash mr-2"></i> {{ __('Delete') }}
        </a>
    </div>
</div>
