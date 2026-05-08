<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use App\Models\Role;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        if (Auth::attempt($this->only('email', 'password'), $this->filled('remember'))) {
            RateLimiter::clear($this->throttleKey());
            return;
        }

        $trainer = Trainer::where('email', $this->input('email'))->first();

        if ($trainer && Hash::check($this->input('password'), $trainer->password)) {
            if ($trainer->status !== 'active') {
                throw ValidationException::withMessages([
                    'email' => __('message.trainer_inactive_account'),
                ]);
            }

            $user = DB::transaction(function () use ($trainer) {
                $role = Role::firstOrCreate(
                    ['name' => 'trainer'],
                    ['title' => 'Trainer', 'status' => 1]
                );

                $nameParts = preg_split('/\s+/', trim($trainer->name ?: $trainer->email), 2);
                $firstName = $nameParts[0] ?? 'Trainer';
                $lastName = $nameParts[1] ?? $firstName;

                $user = $trainer->user ?: User::where('email', $trainer->email)->first();

                if (!$user) {
                    $user = User::create([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'display_name' => $trainer->name ?: trim($firstName . ' ' . $lastName),
                        'username' => stristr($trainer->email, '@', true) . rand(100, 999),
                        'email' => $trainer->email,
                        'phone_number' => $trainer->phone_number,
                        'password' => $trainer->password,
                        'status' => $trainer->status,
                        'user_type' => 'trainer',
                    ]);
                } else {
                    $user->fill([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'display_name' => $trainer->name ?: trim($firstName . ' ' . $lastName),
                        'phone_number' => $trainer->phone_number,
                        'status' => $trainer->status,
                        'user_type' => 'trainer',
                    ]);

                    if (!$user->password || Hash::check($this->input('password'), $trainer->password)) {
                        $user->password = $trainer->password;
                    }

                    $user->save();
                }

                if (!$user->hasRole('trainer')) {
                    $user->assignRole($role);
                }

                if ((int) $trainer->user_id !== (int) $user->id) {
                    $trainer->user_id = $user->id;
                    $trainer->save();
                }

                return $user;
            });

            Auth::login($user, $this->filled('remember'));
            RateLimiter::clear($this->throttleKey());
            return;
        }

        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')).'|'.$this->ip();
    }
}
