<x-app-layout>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">{{ __('message.list_form_title', ['form' => __('message.trainer')]) }}</h4>
                    </div>
                    <div class="card-action">
                        <a href="{{ route('trainers.create') }}" class="btn btn-sm btn-primary" role="button">{{ __('message.add_form_title', ['form' => __('message.trainer')]) }}</a>
                    </div>
                </div>
                <div class="card-body">
                    @if($trainers->isEmpty())
                        <p class="mb-0">{{ __('message.no_record_found') }}</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('message.name') }}</th>
                                        <th>{{ __('message.email') }}</th>
                                        <th>{{ __('message.status') }}</th>
                                        <th class="text-end">{{ __('message.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trainers as $trainer)
                                        <tr>
                                            <td>{{ $trainer->name }}</td>
                                            <td>{{ $trainer->email }}</td>
                                            <td>{{ __('message.' . $trainer->status) }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('trainers.edit', $trainer->id) }}" class="btn btn-sm btn-success">{{ __('message.edit') }}</a>
                                                {{ html()->form('DELETE', route('trainers.destroy', $trainer->id))->class('d-inline')->open() }}
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('message.delete_msg') }}')">{{ __('message.delete') }}</button>
                                                {{ html()->form()->close() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $trainers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
