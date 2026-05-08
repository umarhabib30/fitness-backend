<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class TrainerRequest extends FormRequest
{
    public function authorize()
    {
        // Only admins can manage trainers
        return $this->user()->hasRole('admin');
    }

    protected function prepareForValidation()
    {
        if ($this->has('phone_number')) {
            $this->merge([
                'phone_number' => str_replace('+', '', $this->phone_number),
            ]);
        }
    }

    public function rules()
    {
        $trainer = $this->route('trainer');
        $trainerId = $trainer ? $trainer->id : null;
        $trainerUserId = $trainer?->user_id;

        return [
            'name' => ['required', 'string', 'max:191'],
            'email' => [
                'required',
                'email',
                'max:191',
                Rule::unique('trainers', 'email')->ignore($trainerId),
                Rule::unique('users', 'email')->ignore($trainerUserId),
            ],
            'phone_number' => ['nullable', 'max:20', Rule::unique('trainers', 'phone_number')->ignore($trainerId)],
            'password' => [$trainerId ? 'nullable' : 'required', 'string', 'min:6'],
            'status'  => ['required', 'in:active,inactive,suspended'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()->back()->withInput()->with('errors', $validator->errors())
        );
    }
}
