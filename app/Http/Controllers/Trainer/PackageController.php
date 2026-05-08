<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\TrainerPackage;
use App\Models\TrainerSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    public function index()
    {
        $trainer = auth('trainer')->user();
        $packages = TrainerPackage::where('is_active', true)->orderBy('price')->get();
        $activeSubscription = $trainer->activeSubscription;
        $subscriptions = $trainer->subscriptions()->with('package')->latest()->get();

        return view('trainer.packages.index', compact('trainer', 'packages', 'activeSubscription', 'subscriptions'));
    }

    public function subscribe(Request $request, TrainerPackage $package)
    {
        $trainer = auth('trainer')->user();

        if (!$package->is_active) {
            return redirect()->back()->withErrors(['package' => __('message.not_found_entry', ['name' => __('message.package')])]);
        }

        DB::transaction(function () use ($trainer, $package) {
            $trainer->subscriptions()
                ->where('status', 'active')
                ->update(['status' => 'inactive']);

            TrainerSubscription::create([
                'trainer_id' => $trainer->id,
                'package_id' => $package->id,
                'started_at' => now()->toDateString(),
                'ends_at' => now()->addDays($package->duration_days)->toDateString(),
                'status' => 'active',
            ]);
        });

        return redirect()->route('trainer.dashboard')->withSuccess(__('message.save_form', ['form' => __('message.subscription')]));
    }
}
