<x-layouts.trainer>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('message.package') }}</h4>
                </div>
                <div class="card-body">
                    @if($activeSubscription)
                        <div class="alert alert-success mb-0">
                            {{ $activeSubscription->package->name ?? '-' }} | {{ __('message.subscription_end_date') }}: {{ optional($activeSubscription->ends_at)->format('Y-m-d') }}
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">{{ __('message.trainer_select_package') }}</div>
                    @endif
                </div>
            </div>
        </div>

        @foreach($packages as $package)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4>{{ $package->name }}</h4>
                        <p class="text-muted">{{ $package->description }}</p>
                        <p class="mb-1">{{ __('message.price') }}: {{ number_format($package->price, 2) }}</p>
                        <p class="mb-3">{{ __('message.duration') }}: {{ $package->duration_days }} {{ __('message.days') }}</p>
                        <form method="POST" action="{{ route('trainer.packages.subscribe', $package) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">{{ __('message.subscription') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('message.subscription_history') }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('message.package') }}</th>
                                    <th>{{ __('message.subscription_start_date') }}</th>
                                    <th>{{ __('message.subscription_end_date') }}</th>
                                    <th>{{ __('message.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscriptions as $subscription)
                                    <tr>
                                        <td>{{ $subscription->package->name ?? '-' }}</td>
                                        <td>{{ optional($subscription->started_at)->format('Y-m-d') }}</td>
                                        <td>{{ optional($subscription->ends_at)->format('Y-m-d') }}</td>
                                        <td>{{ ucfirst($subscription->status) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">{{ __('message.no_record_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.trainer>
