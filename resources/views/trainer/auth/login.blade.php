<x-guest-layout>
    <section class="login-content">
        <div class="row m-0 align-items-center bg-white vh-100">
            <div class="col-md-6">
                <div class="row justify-content-center mb-4">
                    <div class="col-md-10">
                        <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                            <div class="card-body">
                                <div class="auth-logo">
                                    <a href="{{ route('trainer.login') }}" class="navbar-brand d-flex align-items-center mb-5">
                                        <img src="{{ getSingleMedia(appSettingData('get'),'site_logo',null) }}" class="img-fluid rounded-normal light-logo" alt="logo">
                                    </a>
                                </div>
                                <h4 class="mb-4">{{ __('message.trainer') }} {{ __('auth.login') }}</h4>
                                <x-auth-session-status class="mb-4" :status="session('status')" />
                                <x-auth-validation-errors class="mb-4" :errors="$errors" />
                                <form method="POST" action="{{ route('trainer.login.store') }}">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">{{ __('message.email') }}</label>
                                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="password" class="form-label">{{ __('message.password') }}</label>
                                        <input id="password" type="password" name="password" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">{{ __('auth.login') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 d-md-block d-none bg-soft-warning p-0 mt-n1 vh-100 overflow-hidden">
                <img src="{{ asset('images/auth/01.png') }}" class="img-fluid gradient-main animated-scaleX" alt="images">
            </div>
        </div>
    </section>
</x-guest-layout>
