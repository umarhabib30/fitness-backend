<x-layouts.trainer>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title mb-0">{{ __('message.list_form_title', ['form' => __('message.user')]) }}</h4>
            <a href="{{ route('trainer.clients.create') }}" class="btn btn-sm btn-primary">{{ __('message.add_form_title', ['form' => __('message.user')]) }}</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('message.name') }}</th>
                            <th>{{ __('message.email') }}</th>
                            <th>{{ __('message.phone_number') }}</th>
                            <th>{{ __('message.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr>
                                <td>{{ $client->display_name }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->phone_number }}</td>
                                <td>{{ ucfirst($client->status) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">{{ __('message.no_record_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $clients->links() }}
            </div>
        </div>
    </div>
</x-layouts.trainer>
