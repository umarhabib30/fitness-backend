<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TrainerPackageDataTable;
use App\Http\Controllers\Controller;
use App\Models\TrainerPackage;
use Illuminate\Http\Request;

class TrainerPackageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(TrainerPackageDataTable $dataTable)
    {
        $pageTitle = 'Trainer Packages';
        $assets = ['data-table'];
        $headerAction = '<a href="' . route('trainer-packages.create') . '" class="btn btn-sm btn-primary" role="button">Add Trainer Package</a>';

        return $dataTable->render('global.datatable', compact('pageTitle', 'assets', 'headerAction'));
    }

    public function create()
    {
        return view('admin.trainer_packages.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        TrainerPackage::create($data);

        return redirect()->route('trainer-packages.index')
                         ->withSuccess('Trainer package created successfully');
    }

    public function edit(TrainerPackage $trainerPackage)
    {
        return view('admin.trainer_packages.edit', compact('trainerPackage'));
    }

    public function update(Request $request, TrainerPackage $trainerPackage)
    {
        $data = $this->validatedData($request);
        $trainerPackage->update($data);

        return redirect()->route('trainer-packages.index')
                         ->withSuccess('Trainer package updated successfully');
    }

    public function destroy(TrainerPackage $trainerPackage)
    {
        $trainerPackage->delete();

        return redirect()->route('trainer-packages.index')
                         ->withSuccess('Trainer package deleted successfully');
    }

    protected function validatedData(Request $request): array
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'interval'      => 'required|in:monthly,yearly',
            'duration_days' => 'required|integer|min:1',
            'features'      => 'nullable|string',
            'is_active'     => 'required|boolean',
        ]);

        $data['features'] = collect(preg_split('/\r\n|\r|\n/', (string) ($data['features'] ?? '')))
            ->map(fn ($feature) => trim($feature))
            ->filter()
            ->values()
            ->all();

        return $data;
    }
}
