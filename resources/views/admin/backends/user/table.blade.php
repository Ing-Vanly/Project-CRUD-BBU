<div class="table-wrapper">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Role') }}</th>
                    <th>{{ __('Created') }}</th>
                    <th width="10%">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $key=>$user)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->roles->isNotEmpty())
                                <span class="badge badge-primary">{{ $user->roles->first()->name }}</span>
                            @else
                                <span class="badge badge-secondary">No Role</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('user.edit', $user->id) }}"
                                    class="btn btn-primary btn-sm mr-2">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form class="form-delete-{{ $user->id }}"
                                    action="{{ route('user.destroy', $user->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" data-id="{{ $user->id }}"
                                        class="btn btn-danger btn-sm btn-delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">{{ __('No data found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $users->links() }}
    </div>
</div>