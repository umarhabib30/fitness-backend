<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\TrainerPackage;

class DashboardController extends Controller
{
    public function index()
    {
        $trainer = auth('trainer')->user();
        $activeSubscription = $trainer->activeSubscription;
        $packages = TrainerPackage::where('is_active', true)->orderBy('price')->get();
        $stats = [
            'clients' => $trainer->clients()->count(),
            'diets' => \App\Models\Diet::where('trainer_id', $trainer->id)->count(),
            'workouts' => \App\Models\Workout::where('trainer_id', $trainer->id)->count(),
            'recipes' => \App\Models\Recipe::where('trainer_id', $trainer->id)->count(),
        ];

        return view('trainer.dashboard', compact('trainer', 'activeSubscription', 'packages', 'stats'));
    }
}
