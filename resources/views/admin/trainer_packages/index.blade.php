<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title mb-0">Trainer Packages</h4>
            <a href="{{ route('trainer-packages.create') }}" class="btn btn-sm btn-primary">Add Trainer Package</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('message.name') }}</th>
                            <th>{{ __('message.price') }}</th>
                            <th>{{ __('message.duration_unit') }}</th>
                            <th>{{ __('message.duration') }}</th>
                            <th>{{ __('message.status') }}</th>
                            <th>{{ __('message.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $package->name }}</div>
                                    @if($package->description)
                                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($package->description, 80) }}</small>
                                    @endif
                                </td>
                                <td>{{ number_format((float) $package->price, 2) }}</td>
                                <td>{{ ucfirst($package->interval) }}</td>
                                <td>
                                    {{ $package->duration_days }}
                                    {{ $package->interval === 'yearly' ? 'Year(s)' : 'Month(s)' }}
                                </td>
                                <td>{{ $package->is_active ? __('message.active') : __('message.inactive') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('trainer-packages.edit', $package) }}" class="btn btn-sm btn-success">{{ __('message.edit') }}</a>
                                        <form action="{{ route('trainer-packages.destroy', $package) }}" method="POST" onsubmit="return confirm('{{ __('message.delete_msg') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">{{ __('message.delete') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No trainer packages found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $packages->links() }}</div>
        </div>
    </div>
</x-app-layout>
