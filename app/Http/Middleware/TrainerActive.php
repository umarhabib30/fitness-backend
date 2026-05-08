<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrainerActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $trainer = auth('trainer')->user();

        if (!$trainer) {
            return redirect()->route('trainer.login');
        }

        if ($trainer->status !== 'active') {
            auth('trainer')->logout();

            return redirect()->route('trainer.login')
                ->withErrors(['email' => __('message.trainer_inactive_account')]);
        }

        if ($trainer && $trainer->activeSubscription && $trainer->activeSubscription->is_active) {
            return $next($request);
        }

        return redirect()->route('trainer.packages.index')
            ->withErrors(['subscription' => __('message.trainer_subscription_inactive')]);
    }
}
