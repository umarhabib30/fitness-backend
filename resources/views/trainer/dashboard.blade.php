<x-layouts.trainer>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">{{ __('message.user') }}</h6>
                    <h3>{{ $stats['clients'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">{{ __('message.diet') }}</h6>
                    <h3>{{ $stats['diets'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">{{ __('message.workout') }}</h6>
                    <h3>{{ $stats['workouts'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">{{ __('message.recipe') }}</h6>
                    <h3>{{ $stats['recipes'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title mb-0">{{ __('message.subscription') }}</h4>
                    <a href="{{ route('trainer.settings.index') }}" class="btn btn-sm btn-primary">{{ __('message.setting') }}</a>
                </div>
                <div class="card-body">
                    @if($activeSubscription)
                        <p class="mb-1"><strong>{{ $activeSubscription->package->name ?? '-' }}</strong></p>
                        <p class="mb-1">{{ __('message.subscription_start_date') }}: {{ optional($activeSubscription->started_at)->format('Y-m-d') }}</p>
                        <p class="mb-0">{{ __('message.subscription_end_date') }}: {{ optional($activeSubscription->ends_at)->format('Y-m-d') }}</p>
                    @else
                        <div class="alert alert-warning mb-0">{{ __('message.trainer_no_active_package') }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('message.package') }}</h4>
                </div>
                <div class="card-body">
                    @foreach($packages->take(3) as $package)
                        <div class="border rounded p-3 mb-3">
                            <h6 class="mb-1">{{ $package->name }}</h6>
                            <div class="text-muted small mb-2">{{ number_format((float) $package->price, 2) }}</div>
                        </div>
                    @endforeach
                    <a href="{{ route('trainer.settings.index') }}" class="btn btn-sm btn-primary">{{ __('message.setting') }}</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.trainer>
