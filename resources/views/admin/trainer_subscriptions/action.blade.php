<div class="d-flex align-items-center gap-2 justify-content-center">
    @if($subscription->status === 'pending')
        {{ html()->form('POST', route('trainer-subscriptions.approve', $subscription))->class('d-inline')->open() }}
            <button type="submit" class="btn btn-sm btn-success">Approve</button>
        {{ html()->form()->close() }}

        {{ html()->form('POST', route('trainer-subscriptions.reject', $subscription))->class('d-inline')->open() }}
            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
        {{ html()->form()->close() }}
    @else
        <span class="text-muted small">No action</span>
    @endif
</div>
