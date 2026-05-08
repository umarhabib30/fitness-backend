<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title mb-0">{{ __('message.list_form_title', ['form' => __('message.package')]) }}</h4>
            <a href="{{ route('trainer-packages.create') }}" class="btn btn-sm btn-primary">{{ __('message.add_form_title', ['form' => __('message.package')]) }}</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('message.name') }}</th>
                            <th>{{ __('message.price') }}</th>
                            <th>{{ __('message.duration') }}</th>
                            <th>{{ __('message.status') }}</th>
                            <th>{{ __('message.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packages as $package)
                            <tr>
                                <td>{{ $package->name }}</td>
                                <td>{{ number_format($package->price, 2) }}</td>
                                <td>{{ $package->duration_days }} {{ __('message.days') }}</td>
                                <td>{{ $package->is_active ? __('message.active') : __('message.inactive') }}</td>
                                <td>
                                    <a href="{{ route('trainer-packages.edit', $package) }}" class="btn btn-sm btn-success">{{ __('message.edit') }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $packages->links() }}</div>
        </div>
    </div>
</x-app-layout>
