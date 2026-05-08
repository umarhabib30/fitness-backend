<x-layouts.trainer>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title mb-0">{{ __('message.add_form_title', ['form' => __('message.user')]) }}</h4>
            <a href="{{ route('trainer.clients.index') }}" class="btn btn-sm btn-primary">{{ __('message.back') }}</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('trainer.clients.store') }}">
                @csrf
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="form-control-label">{{ __('message.first_name') }}</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-control-label">{{ __('message.last_name') }}</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-control-label">{{ __('message.email') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-control-label">{{ __('message.phone_number') }}</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-control-label">{{ __('message.password') }}</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-control-label">{{ __('message.gender') }}</label>
                        <select name="gender" class="form-control">
                            <option value="male">{{ __('message.male') }}</option>
                            <option value="female">{{ __('message.female') }}</option>
                            <option value="other">{{ __('message.other') }}</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('message.save') }}</button>
            </form>
        </div>
    </div>
</x-layouts.trainer>
