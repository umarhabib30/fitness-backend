<?php

namespace App\Http\Controllers\Trainer\Auth;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('trainer.auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $trainer = Trainer::where('email', $credentials['email'])->first();

        if (!$trainer || !Hash::check($credentials['password'], $trainer->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        if ($trainer->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => __('message.trainer_inactive_account'),
            ]);
        }

        Auth::guard('trainer')->login($trainer, false);
        $request->session()->regenerate();

        return redirect()->route('trainer.dashboard');
    }

    public function destroy(Request $request)
    {
        Auth::guard('trainer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('trainer.login');
    }
}
