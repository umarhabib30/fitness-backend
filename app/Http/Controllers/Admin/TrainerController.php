<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TrainerDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\TrainerRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Trainer;
use App\Models\User;
use App\Support\TrainerPermissions;
use Illuminate\Support\Facades\DB;

class TrainerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(TrainerDataTable $dataTable)
    {
        $pageTitle = __('message.list_form_title', ['form' => __('message.trainer')]);
        $assets = ['data-table'];
        $headerAction = '<a href="' . route('trainers.create') . '" class="btn btn-sm btn-primary" role="button">' . __('message.add_form_title', ['form' => __('message.trainer')]) . '</a>';

        return $dataTable->render('global.datatable', compact('pageTitle', 'assets', 'headerAction'));
    }

    public function create()
    {
        $pageTitle = __('message.add_form_title', ['form' => __('message.trainer')]);
        $assets = ['phone'];

        return view('admin.trainers.create', compact('pageTitle', 'assets'));
    }

    public function store(TrainerRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $role = Role::firstOrCreate(
                ['name' => 'trainer'],
                ['title' => 'Trainer', 'status' => 1]
            );

            foreach (TrainerPermissions::PERMISSIONS as $permissionName) {
                Permission::firstOrCreate(['name' => $permissionName], ['title' => ucwords(str_replace('-', ' ', $permissionName))]);
            }

            $role->syncPermissions(TrainerPermissions::PERMISSIONS);

            $nameParts = preg_split('/\s+/', trim($data['name']), 2);
            $firstName = $nameParts[0] ?? $data['name'];
            $lastName = $nameParts[1] ?? $nameParts[0] ?? $data['name'];

            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'display_name' => $data['name'],
                'username' => stristr($data['email'], '@', true) . rand(100, 999),
                'email' => $data['email'],
                'phone_number' => $data['phone_number'] ?? null,
                'password' => bcrypt($data['password']),
                'status' => $data['status'],
                'user_type' => 'trainer',
                'email_verified_at' => now(),
            ]);

            $user->assignRole('trainer');

            Trainer::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'email' => $data['email'],
                'phone_number' => $data['phone_number'] ?? null,
                'password' => $user->password,
                'status' => $data['status'],
            ]);
        });

        return redirect()->route('trainers.index')
                        ->withSuccess(__('message.save_form', ['form' => __('message.trainer')]));
    }

    public function edit(Trainer $trainer)
    {
        $pageTitle = __('message.update_form_title', ['form' => __('message.trainer')]);
        $assets = ['phone'];
        $data = $trainer;
        $id = $trainer->id;

        return view('admin.trainers.edit', compact('trainer', 'pageTitle', 'assets', 'data', 'id'));
    }

    public function update(TrainerRequest $request, Trainer $trainer)
    {
        $data = $request->validated();

        DB::transaction(function () use ($trainer, $data) {
            $trainer->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone_number' => $data['phone_number'] ?? null,
                'status' => $data['status'],
                'password' => $data['password'] ?? $trainer->password,
            ]);

            if ($trainer->user) {
                $nameParts = preg_split('/\s+/', trim($data['name']), 2);
                $trainer->user->fill([
                    'first_name' => $nameParts[0] ?? $data['name'],
                    'last_name' => $nameParts[1] ?? $nameParts[0] ?? $data['name'],
                    'display_name' => $data['name'],
                    'email' => $data['email'],
                    'phone_number' => $data['phone_number'] ?? null,
                    'status' => $data['status'],
                ]);

                if (!empty($data['password'])) {
                    $trainer->user->password = bcrypt($data['password']);
                }

                $trainer->user->save();
            }
        });

        return redirect()->route('trainers.index')
                        ->withSuccess(__('message.update_form', ['form' => __('message.trainer')]));
    }

    public function destroy(Trainer $trainer)
    {
        $trainer->user?->delete();
        $trainer->delete();

        return redirect()->route('trainers.index')
                        ->withSuccess(__('message.delete_form', ['form' => __('message.trainer')]));
    }

    /**
     * Manually toggle trainer status (active/inactive).
     */
    public function toggleStatus(Trainer $trainer)
    {
        $trainer->status = $trainer->status === 'active' ? 'inactive' : 'active';
        $trainer->save();
        return back()->withSuccess(__('message.status_updated'));
    }
}
