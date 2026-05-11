<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ mighty_language_direction() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Trainer</title>
    <link rel="shortcut icon" href="{{ getSingleMedia(appSettingData('get'), 'site_favicon', null) }}" />
    <link rel="stylesheet" href="{{ asset('css/libs.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hope-ui.css?v=1.0') }}">
    @if(mighty_language_direction() == 'rtl')
        <link rel="stylesheet" href="{{ asset('css/rtl.css') }}">
    @endif
</head>
<body>
    @php
        $trainerUser = auth('trainer')->user();
        $hasActiveSubscription = $trainerUser && $trainerUser->activeSubscription && $trainerUser->activeSubscription->is_active;
    @endphp
    <div id="loading">
        @include('partials.dashboard._body_loader')
    </div>
    <div class="wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-3">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('trainer.dashboard') }}">
                <img src="{{ getSingleMedia(appSettingData('get'),'site_logo',null) }}" class="img-fluid rounded-normal light-logo" alt="logo" style="height: 36px;">
            </a>
            <div class="ms-auto d-flex align-items-center gap-3">
                @if($hasActiveSubscription)
                    <a href="{{ route('trainer.dashboard') }}" class="btn btn-sm btn-soft-primary">{{ __('message.dashboard') }}</a>
                    <a href="{{ route('trainer.clients.index') }}" class="btn btn-sm btn-soft-primary">{{ __('message.user') }}</a>
                @endif
                <a href="{{ route('trainer.settings.index') }}" class="btn btn-sm btn-soft-primary">{{ __('message.setting') }}</a>
                <span class="text-muted small">
                    {{ auth('trainer')->user()->name ?? '' }}
                    @if ($trainerUser && $trainerUser->activeSubscription)
                        <span class="badge bg-success ms-2">{{ __('message.active') }} ({{ optional($trainerUser->activeSubscription->ends_at)->format('Y-m-d') }})</span>
                    @elseif ($trainerUser && $trainerUser->subscriptions()->where('status','pending')->exists())
                        <span class="badge bg-warning ms-2">{{ __('message.pending_approval') }}</span>
                    @elseif ($trainerUser && $trainerUser->subscriptions()->where('status','rejected')->exists())
                        @php $rej = $trainerUser->subscriptions()->where('status','rejected')->latest()->first(); @endphp
                        <span class="badge bg-danger ms-2">{{ __('message.rejected') }}: {{ $rej->rejection_reason ?? '' }}</span>
                    @else
                        <span class="badge bg-secondary ms-2">{{ __('message.no_subscription') }}</span>
                    @endif
                </span>
                <form method="POST" action="{{ route('trainer.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary">{{ __('message.logout') }}</button>
                </form>
            </div>
        </nav>
        <main class="container py-4">
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            {{ $slot }}
        </main>
    </div>
    @include('partials.dashboard._scripts')
</body>
</html>
