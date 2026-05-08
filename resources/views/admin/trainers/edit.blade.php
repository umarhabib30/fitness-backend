<x-app-layout :assets="$assets ?? []">
    <div>
        {{ html()->modelForm($data, 'PATCH', route('trainers.update', $id))->open() }}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">{{ $pageTitle }}</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('trainers.index') }}" class="btn btn-sm btn-primary" role="button">{{ __('message.back') }}</a>
                        </div>
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

                        <div class="row">
                            <div class="form-group col-md-6">
                                {{ html()->label(__('message.name') . ' <span class="text-danger">*</span>', 'name')->class('form-control-label') }}
                                {{ html()->text('name')->value(old('name', $trainer->name))->placeholder(__('message.name'))->class('form-control')->attribute('required', 'required') }}
                            </div>

                            <div class="form-group col-md-6">
                                {{ html()->label(__('message.email') . ' <span class="text-danger">*</span>', 'email')->class('form-control-label') }}
                                {{ html()->email('email')->value(old('email', $trainer->email))->placeholder(__('message.email'))->class('form-control')->attribute('required', 'required') }}
                            </div>

                            <div class="form-group col-md-6">
                                {{ html()->label(__('message.phone_number'), 'phone_number')->class('form-control-label') }}
                                {{ html()->text('phone_number')->value(old('phone_number', $trainer->phone_number))->placeholder(__('message.phone_number'))->class('form-control')->id('number') }}
                            </div>

                            <div class="form-group col-md-6">
                                {{ html()->label(__('message.password'), 'password')->class('form-control-label') }}
                                {{ html()->password('password')->placeholder(__('message.password'))->class('form-control') }}
                                <small class="text-muted">{{ __('message.leave_blank_to_keep_current_password') }}</small>
                            </div>

                            <div class="form-group col-md-6">
                                {{ html()->label(__('message.status') . ' <span class="text-danger">*</span>', 'status')->class('form-control-label') }}
                                {{ html()->select('status', [
                                    'active' => __('message.active'),
                                    'inactive' => __('message.inactive'),
                                    'suspended' => __('message.suspended'),
                                ], old('status', $trainer->status))->class('form-control select2js')->attribute('required', 'required') }}
                            </div>
                        </div>

                        <hr>
                        {{ html()->submit(__('message.update'))->class('btn btn-md btn-primary float-end') }}
                    </div>
                </div>
            </div>
        </div>
        {{ html()->closeModelForm() }}
    </div>
</x-app-layout>
