<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\TrainerPackage;

class SettingController extends Controller
{
    public function index()
    {
        $trainer = auth('trainer')->user();
        $packages = TrainerPackage::where('is_active', true)->orderBy('price')->get();
        $activeSubscription = $trainer->activeSubscription;
        $subscriptions = $trainer->subscriptions()->with('package')->latest()->get();
        $pendingSubscription = $trainer->subscriptions()->where('status', 'pending')->latest()->first();

        return view('trainer.settings.index', compact('trainer', 'packages', 'activeSubscription', 'subscriptions', 'pendingSubscription'));
    }
}
