<x-layouts.trainer>
    <div class="row g-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Trainer Subscription Settings</h4>
                </div>
                <div class="card-body">
                    @if($activeSubscription)
                        <div class="alert alert-success mb-3">
                            Active Trainer Package: <strong>{{ $activeSubscription->package->name ?? '-' }}</strong>
                            <br>
                            {{ __('message.subscription_end_date') }}: {{ optional($activeSubscription->ends_at)->format('Y-m-d') }}
                        </div>
                    @elseif($pendingSubscription)
                        <div class="alert alert-warning mb-3">
                            Your trainer subscription request for <strong>{{ $pendingSubscription->package->name ?? '-' }}</strong> is pending admin approval.
                        </div>
                    @else
                        <div class="alert alert-warning mb-3">
                            No active trainer subscription found. Choose a trainer package below.
                        </div>
                    @endif

                    <p class="text-muted mb-0">This section is only for trainer subscriptions. Manual payment is enabled, so upload payment proof and wait for admin approval.</p>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-1">Purchase Trainer Subscription</h4>
                        <p class="text-muted mb-0">Choose a trainer package below and upload your payment proof for admin approval.</p>
                    </div>
                </div>
            </div>
        </div>

        @foreach($packages as $package)
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h4 class="mb-2">{{ $package->name }}</h4>
                        <p class="text-muted">{{ $package->description }}</p>
                        @if(!empty($package->features))
                            <ul class="mb-3">
                                @foreach($package->features as $feature)
                                    <li>{{ $feature }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <p class="mb-1">{{ __('message.price') }}: {{ number_format((float) $package->price, 2) }}</p>
                        <p class="mb-3">{{ __('message.duration') }}: {{ $package->duration_days }} {{ __('message.days') }} ({{ ucfirst($package->interval) }})</p>
                        <form method="POST" action="{{ route('trainer.packages.subscribe', $package) }}" enctype="multipart/form-data" class="mt-auto">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Payment Proof</label>
                                <input type="file" name="payment_proof" class="form-control" accept="image/*" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" @disabled($pendingSubscription)>Request Package</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Trainer Subscription History</h4>
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
                                    <th>Payment Proof</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscriptions as $subscription)
                                    <tr>
                                        <td>{{ $subscription->package->name ?? '-' }}</td>
                                        <td>{{ optional($subscription->started_at)->format('Y-m-d') }}</td>
                                        <td>{{ optional($subscription->ends_at)->format('Y-m-d') }}</td>
                                        <td>{{ ucfirst($subscription->status) }}</td>
                                        <td>
                                            @if(getMediaFileExit($subscription, 'payment_proof'))
                                                @php($proofUrl = getSingleMedia($subscription, 'payment_proof', false))
                                                <a href="{{ $proofUrl }}" target="_blank" rel="noopener noreferrer">View</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">{{ __('message.no_record_found') }}</td>
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
