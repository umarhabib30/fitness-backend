<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title mb-0">{{ $package ? 'Edit Trainer Package' : 'Add Trainer Package' }}</h4>
            <a href="{{ route('trainer-packages.index') }}" class="btn btn-sm btn-primary">{{ __('message.back') }}</a>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ $action }}">
                @csrf
                @if($method !== 'POST')
                    @method($method)
                @endif
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="form-control-label">{{ __('message.name') }}</label>
                        <input type="text" name="name" value="{{ old('name', $package->name ?? '') }}" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-control-label">{{ __('message.price') }}</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $package->price ?? '') }}" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-control-label">{{ __('message.duration') }} <small class="text-muted">(count of months/years)</small></label>
                        <input type="number" name="duration_days" value="{{ old('duration_days', $package->duration_days ?? 1) }}" class="form-control" min="1" required>
                        <small class="text-muted">e.g. enter <strong>1</strong> for Monthly = 1 month, or <strong>2</strong> for Yearly = 2 years</small>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-control-label">{{ __('message.duration_unit') }}</label>
                        <select name="interval" class="form-control" required>
                            <option value="monthly" @selected(old('interval', $package->interval ?? 'monthly') === 'monthly')>{{ __('message.monthly') }}</option>
                            <option value="yearly" @selected(old('interval', $package->interval ?? '') === 'yearly')>{{ __('message.yearly') }}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="form-control-label">{{ __('message.description') }}</label>
                        <textarea name="description" class="form-control">{{ old('description', $package->description ?? '') }}</textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-control-label">{{ __('message.status') }}</label>
                        <select name="is_active" class="form-control" required>
                            <option value="1" @selected((string) old('is_active', $package->is_active ?? 1) === '1')>{{ __('message.active') }}</option>
                            <option value="0" @selected((string) old('is_active', $package->is_active ?? 1) === '0')>{{ __('message.inactive') }}</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">{{ $package ? __('message.update') : __('message.save') }}</button>
            </form>
        </div>
    </div>
</x-app-layout>
