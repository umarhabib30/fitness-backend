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
    <div id="loading">
        @include('partials.dashboard._body_loader')
    </div>
    <div class="wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-3">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('trainer.dashboard') }}">
                <img src="{{ getSingleMedia(appSettingData('get'),'site_logo',null) }}" class="img-fluid rounded-normal light-logo" alt="logo" style="height: 36px;">
            </a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <a href="{{ route('trainer.dashboard') }}" class="btn btn-sm btn-soft-primary">{{ __('message.dashboard') }}</a>
                <a href="{{ route('trainer.packages.index') }}" class="btn btn-sm btn-soft-primary">{{ __('message.package') }}</a>
                <a href="{{ route('trainer.clients.index') }}" class="btn btn-sm btn-soft-primary">{{ __('message.user') }}</a>
                <span class="text-muted small">{{ auth('trainer')->user()->name ?? '' }}</span>
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
