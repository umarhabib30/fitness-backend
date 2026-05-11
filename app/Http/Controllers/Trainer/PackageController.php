<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\TrainerPackage;
use App\Models\TrainerSubscription;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:trainer']);
    }

    public function index()
    {
        $trainerProfile = auth()->user()->trainerProfile;

        $packages = TrainerPackage::where('is_active', true)->get();

        $activeSubscription = $trainerProfile
            ? $trainerProfile->activeSubscription
            : null;

        $pendingSubscription = $trainerProfile
            ? $trainerProfile->subscriptions()->where('status', 'pending')->with('package')->first()
            : null;

        $subscriptions = $trainerProfile
            ? $trainerProfile->subscriptions()->with('package')->latest()->get()
            : collect();

        return view('trainer.packages.index', compact(
            'packages',
            'activeSubscription',
            'pendingSubscription',
            'subscriptions'
        ));
    }

    public function subscribe(Request $request, TrainerPackage $package)
    {
        $trainerProfile = auth()->user()->trainerProfile;

        if (!$trainerProfile) {
            return redirect()->back()->withErrors(['subscription' => 'Trainer profile not found.']);
        }

        $request->validate([
            'payment_proof'         => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:4096',
            'transaction_reference' => 'nullable|string|max:255',
        ]);

        if (!$package->is_active) {
            return redirect()->back()->withErrors(['package' => __('message.not_found_entry', ['name' => __('message.package')])]);
        }

        if ($trainerProfile->subscriptions()->where('status', 'pending')->exists()) {
            return redirect()->back()->withErrors(['subscription' => 'You already have a pending package request awaiting admin approval.']);
        }

        $subscription = TrainerSubscription::create([
            'trainer_id'            => $trainerProfile->id,
            'package_id'            => $package->id,
            'started_at'            => now()->toDateString(),
            'ends_at'               => null,
            'status'                => 'pending',
            'transaction_reference' => $request->input('transaction_reference'),
        ]);

        storeMediaFile($subscription, $request->payment_proof, 'payment_proof');

        return redirect()->route('trainer.packages.index')
            ->withSuccess('Package request submitted successfully. Please wait for admin approval.');
    }
}
