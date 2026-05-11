<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\TrainerSubscriptionDataTable;
use App\Http\Controllers\Controller;
use App\Models\TrainerSubscription;
use Illuminate\Support\Facades\DB;

class TrainerSubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(TrainerSubscriptionDataTable $dataTable)
    {
        $pageTitle = 'Trainer Subscriptions';
        $assets = ['data-table'];

        return $dataTable->render('global.datatable', compact('pageTitle', 'assets'));
    }

    public function approve(TrainerSubscription $trainerSubscription)
    {
        if ($trainerSubscription->status !== 'pending') {
            return redirect()->route('trainer-subscriptions.index')->withErrors(['subscription' => 'Only pending trainer subscriptions can be approved.']);
        }

        DB::transaction(function () use ($trainerSubscription) {
            $trainerSubscription->trainer->subscriptions()
                ->where('status', 'active')
                ->where('id', '!=', $trainerSubscription->id)
                ->update([
                    'status' => 'inactive',
                    'reviewed_at' => now(),
                ]);

            $package  = $trainerSubscription->package;
            $duration = (int) $package->duration_days; // count of units (e.g. 1, 2, 3)
            $endsAt   = match ($package->interval) {
                'monthly' => now()->addMonths($duration),
                'yearly'  => now()->addYears($duration),
                default   => now()->addDays($duration),   // fallback
            };

            $trainerSubscription->update([
                'status'     => 'active',
                'started_at' => now()->toDateString(),
                'ends_at'    => $endsAt->toDateString(),
                'reviewed_at'=> now(),
            ]);
        });

        return redirect()->route('trainer-subscriptions.index')->withSuccess('Trainer subscription approved successfully');
    }

    public function reject(TrainerSubscription $trainerSubscription)
    {
        if ($trainerSubscription->status !== 'pending') {
            return redirect()->route('trainer-subscriptions.index')->withErrors(['subscription' => 'Only pending trainer subscriptions can be rejected.']);
        }

        $trainerSubscription->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
        ]);

        return redirect()->route('trainer-subscriptions.index')->withSuccess('Trainer subscription rejected successfully');
    }
}
