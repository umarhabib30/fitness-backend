<x-app-layout>

    {{-- ───────────────────── Styles ───────────────────── --}}
    <style>
        .package-card {
            background: var(--bs-body-bg, #fff);
            border: 1px solid var(--bs-border-color, #e5e7eb);
            border-radius: 16px;
            padding: 0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: box-shadow .2s ease, transform .2s ease;
        }

        .package-card:hover {
            box-shadow: 0 8px 32px rgba(0, 0, 0, .08);
            transform: translateY(-2px);
        }

        /* Header */
        .package-header {
            padding: 1.75rem 1.5rem 1rem;
            border-bottom: 1px solid var(--bs-border-color, #e5e7eb);
        }

        .package-badge {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--bs-primary-bg-subtle, #e9f0ff);
            color: var(--bs-primary, #0d6efd);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 1rem;
        }

        .package-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: .35rem;
            color: var(--bs-body-color);
        }

        .package-desc {
            font-size: .875rem;
            color: var(--bs-secondary-color, #6c757d);
            margin: 0;
            line-height: 1.5;
        }

        /* Features */
        .feature-list {
            list-style: none;
            padding: 1.1rem 1.5rem;
            margin: 0;
            border-bottom: 1px solid var(--bs-border-color, #e5e7eb);
            display: flex;
            flex-direction: column;
            gap: .55rem;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: .5rem;
            font-size: .875rem;
            color: var(--bs-body-color);
        }

        .feature-item .ti {
            font-size: 17px;
            color: var(--bs-primary, #0d6efd);
            margin-top: 1px;
            flex-shrink: 0;
        }

        /* Pricing */
        .pricing-block {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--bs-border-color, #e5e7eb);
            display: flex;
            flex-direction: column;
            gap: .4rem;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: .875rem;
        }

        .price-label {
            color: var(--bs-secondary-color, #6c757d);
        }

        .price-value {
            font-weight: 600;
            color: var(--bs-body-color);
        }

        /* Form */
        .package-form {
            padding: 1.25rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            flex: 1;
        }

        /* Upload zone */
        .upload-zone {
            position: relative;
        }

        .upload-input {
            position: absolute;
            inset: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 2;
        }

        .upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .3rem;
            padding: 1.1rem 1rem;
            border: 1.5px dashed var(--bs-border-color, #dee2e6);
            border-radius: 10px;
            background: var(--bs-tertiary-bg, #f8f9fa);
            cursor: pointer;
            transition: border-color .18s, background .18s;
            text-align: center;
        }

        .upload-zone:hover .upload-label,
        .upload-input:focus+.upload-label {
            border-color: var(--bs-primary, #0d6efd);
            background: var(--bs-primary-bg-subtle, #e9f0ff);
        }

        .upload-icon {
            font-size: 26px;
            color: var(--bs-primary, #0d6efd);
        }

        .upload-text {
            font-size: .8rem;
            font-weight: 600;
            color: var(--bs-body-color);
        }

        .upload-hint {
            font-size: .72rem;
            color: var(--bs-secondary-color, #6c757d);
        }

        /* Subscribe button */
        .btn-subscribe {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            padding: .7rem 1rem;
            border: none;
            border-radius: 10px;
            background: var(--bs-primary, #0d6efd);
            color: #fff;
            font-size: .9rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .18s, opacity .18s, transform .1s;
        }

        .btn-subscribe:hover:not(:disabled) {
            background: var(--bs-primary-text-emphasis, #0a58ca);
            transform: translateY(-1px);
        }

        .btn-subscribe:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-subscribe:disabled {
            opacity: .55;
            cursor: not-allowed;
        }

        .btn-subscribe .ti {
            font-size: 18px;
        }
    </style>

    <div class="row g-4">

        {{-- Status Banner --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">My Subscription</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success mb-3">{{ session('success') }}</div>
                    @endif

                    @if ($activeSubscription)
                        <div class="alert alert-success mb-0">
                            <strong>Active Package:</strong> {{ $activeSubscription->package->name ?? '-' }}<br>
                            <strong>{{ __('message.subscription_end_date') }}:</strong>
                            {{ optional($activeSubscription->ends_at)->format('Y-m-d') }}
                        </div>
                    @elseif($pendingSubscription)
                        <div class="alert alert-warning mb-0">
                            Your subscription request for
                            <strong>{{ $pendingSubscription->package->name ?? '-' }}</strong>
                            is <strong>pending admin approval</strong>. Please wait.
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            No active subscription. Choose a package below and upload payment proof.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Available Packages --}}
        @if ($packages->isNotEmpty())

            @foreach ($packages as $package)
                <div class="col-lg-4 col-md-6">
                    <div class="package-card h-100">

                        {{-- Header --}}
                        <div class="package-header">
                            <div class="package-badge">
                                <i class="ti ti-package" aria-hidden="true"></i>
                            </div>
                            <h4 class="package-title">{{ $package->name }}</h4>
                            <p class="package-desc">{{ $package->description }}</p>
                        </div>

                        {{-- Features --}}


                        {{-- Pricing --}}
                        <div class="pricing-block">
                            <div class="price-row">
                                <span class="price-label">{{ __('message.price') }}</span>
                                <span class="price-value">{{ number_format((float) $package->price, 2) }}</span>
                            </div>
                            <div class="price-row">
                                <span class="price-label">{{ __('message.duration') }}</span>
                                <span class="price-value">
                                    {{ $package->duration_days }}
                                    {{ $package->interval === 'yearly' ? 'Year(s)' : 'Month(s)' }}
                                </span>
                            </div>
                        </div>

                        {{-- Form --}}
                        <form method="POST" action="{{ route('trainer.packages.subscribe', $package) }}"
                            enctype="multipart/form-data" class="package-form mt-auto">
                            @csrf

                            {{-- Upload zone --}}
                            <div class="upload-zone" id="zone_{{ $package->id }}">
                                <input type="file" name="payment_proof" id="payment_proof_{{ $package->id }}"
                                    class="upload-input" accept=".jpg,.jpeg,.png,.webp,.pdf" required
                                    onchange="updateUploadLabel(this, '{{ $package->id }}')">
                                <label for="payment_proof_{{ $package->id }}" class="upload-label"
                                    id="label_{{ $package->id }}">
                                    <i class="ti ti-cloud-upload upload-icon" aria-hidden="true"></i>
                                    <span class="upload-text">{{ __('message.upload_payment_proof') }}</span>
                                    <span class="upload-hint">JPG, PNG, WEBP or PDF</span>
                                </label>
                            </div>

                            <button type="submit" class="btn-subscribe w-100" @disabled($pendingSubscription)>
                                @if ($pendingSubscription)
                                    <i class="ti ti-clock" aria-hidden="true"></i>
                                    {{ __('message.pending') }}
                                @else
                                    <i class="ti ti-bolt" aria-hidden="true"></i>
                                    {{ __('message.subscribe') }}
                                @endif
                            </button>
                        </form>

                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="alert alert-info">No active packages are available at the moment. Please contact the admin.
                </div>
            </div>
        @endif

        {{-- Subscription History --}}
        <div class="col-12">
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
                                    <th>Payment Proof</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscriptions as $subscription)
                                    <tr>
                                        <td>{{ $subscription->package->name ?? '-' }}</td>
                                        <td>{{ optional($subscription->started_at)->format('Y-m-d') }}</td>
                                        <td>{{ optional($subscription->ends_at)->format('Y-m-d') }}</td>
                                        <td>
                                            @php
                                                $badge = match ($subscription->status) {
                                                    'active' => 'success',
                                                    'pending' => 'warning',
                                                    'rejected', 'expired', 'canceled' => 'danger',
                                                    default => 'secondary',
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $badge }} text-capitalize">
                                                {{ $subscription->status }}
                                            </span>
                                        </td>
                                        <td>
                                            @if (getMediaFileExit($subscription, 'payment_proof'))
                                                @php($proofUrl = getSingleMedia($subscription, 'payment_proof', false))
                                                <a href="{{ $proofUrl }}" target="_blank"
                                                    rel="noopener noreferrer">View</a>
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


    <script>
        function updateUploadLabel(input, packageId) {
            const label = document.getElementById('label_' + packageId);
            const file = input.files[0];

            if (file) {
                const name = file.name.length > 28 ? file.name.substring(0, 25) + '…' : file.name;
                const size = file.size < 1024 * 1024 ?
                    (file.size / 1024).toFixed(1) + ' KB' :
                    (file.size / (1024 * 1024)).toFixed(1) + ' MB';

                label.innerHTML = `
            <i class="ti ti-file-check upload-icon" aria-hidden="true" style="color: var(--bs-success, #198754);"></i>
            <span class="upload-text" style="color: var(--bs-success, #198754);">${name}</span>
            <span class="upload-hint">${size} — click to change</span>
        `;
                label.style.borderColor = 'var(--bs-success, #198754)';
                label.style.background = 'var(--bs-success-bg-subtle, #d1e7dd)';
            } else {
                label.innerHTML = `
            <i class="ti ti-cloud-upload upload-icon" aria-hidden="true"></i>
            <span class="upload-text">{{ __('message.upload_payment_proof') }}</span>
            <span class="upload-hint">JPG, PNG, WEBP or PDF</span>
        `;
                label.style.borderColor = '';
                label.style.background = '';
            }
        }
    </script>
</x-app-layout>
