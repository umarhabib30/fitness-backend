<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;

class ClientController extends Controller
{
    public function index()
    {
        $trainer = auth('trainer')->user();
        $clients = User::where('trainer_id', $trainer->id)
            ->where('user_type', 'user')
            ->latest('id')
            ->paginate(20);

        return view('trainer.clients.index', compact('trainer', 'clients'));
    }

    public function create()
    {
        return view('trainer.clients.create');
    }

    public function store(UserRequest $request)
    {
        $trainer = auth('trainer')->user();

        $input = $request->validated();
        $input['password'] = bcrypt($request->password);
        $input['username'] = $request->username ?? stristr($request->email, "@", true) . rand(100, 1000);
        $input['display_name'] = trim(($request->first_name ?? '') . ' ' . ($request->last_name ?? ''));
        $input['user_type'] = 'user';
        $input['status'] = $request->status ?? 'active';
        $input['trainer_id'] = $trainer->id;

        $user = User::create($input);
        $user->assignRole('user');

        return redirect()->route('trainer.clients.index')->withSuccess(__('message.save_form', ['form' => __('message.user')]));
    }
}
