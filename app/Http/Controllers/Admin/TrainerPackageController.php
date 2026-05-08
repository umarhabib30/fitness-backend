<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrainerPackageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $packages = \App\Models\TrainerPackage::paginate(20);
        return view('admin.trainer_packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.trainer_packages.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'interval'    => 'required|in:monthly,yearly',
            'duration_days'=> 'required|integer|min:1',
            'features'    => 'nullable|array',
            'is_active'   => 'required|boolean',
        ]);
        \App\Models\TrainerPackage::create($data);
        return redirect()->route('trainer-packages.index')
                         ->withSuccess(__('Package created successfully'));
    }

    public function edit(\App\Models\TrainerPackage $trainerPackage)
    {
        return view('admin.trainer_packages.edit', compact('trainerPackage'));
    }

    public function update(Request $request, \App\Models\TrainerPackage $trainerPackage)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'interval'    => 'required|in:monthly,yearly',
            'duration_days'=> 'required|integer|min:1',
            'features'    => 'nullable|array',
            'is_active'   => 'required|boolean',
        ]);
        $trainerPackage->update($data);
        return redirect()->route('trainer-packages.index')
                         ->withSuccess(__('Package updated successfully'));
    }

    public function destroy(\App\Models\TrainerPackage $trainerPackage)
    {
        $trainerPackage->delete();
        return redirect()->route('trainer-packages.index')
                         ->withSuccess(__('Package deleted successfully'));
    }
}
