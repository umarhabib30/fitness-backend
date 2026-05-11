<?php

namespace App\DataTables\Admin;

use App\Models\TrainerSubscription;
use App\Traits\DataTableTrait;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TrainerSubscriptionDataTable extends DataTable
{
    use DataTableTrait;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('trainer_name', fn ($subscription) => $subscription->trainer->user->name ?? $subscription->trainer->name ?? '-')
            ->addColumn('trainer_email', fn ($subscription) => $subscription->trainer->user->email ?? $subscription->trainer->email ?? '-')
            ->addColumn('transaction_reference', fn($subscription) => $subscription->transaction_reference ?? '-')
            ->editColumn('status', function ($subscription) {
                $statusClass = match ($subscription->status) {
                    'active' => 'primary',
                    'pending' => 'warning',
                    'rejected', 'expired', 'canceled' => 'danger',
                    default => 'secondary',
                };

                return '<span class="text-capitalize badge bg-' . $statusClass . '">' . $subscription->status . '</span>';
            })
            ->addColumn('payment_proof', function ($subscription) {
                if (!getMediaFileExit($subscription, 'payment_proof')) {
                    return '-';
                }

                $proofUrl = getSingleMedia($subscription, 'payment_proof', false);

                return '<a href="' . $proofUrl . '" target="_blank" rel="noopener noreferrer">View Proof</a>';
            })
            ->editColumn('started_at', fn ($subscription) => optional($subscription->started_at)->format('Y-m-d'))
            ->editColumn('ends_at', fn ($subscription) => optional($subscription->ends_at)->format('Y-m-d'))
            ->editColumn('created_at', fn ($subscription) => dateAgoFormate($subscription->created_at, true))
            ->addColumn('action', function ($subscription) {
                return view('admin.trainer_subscriptions.action', compact('subscription'))->render();
            })
            ->order(function ($query) {
                if (request()->has('order')) {
                    $order = request()->order[0];
                    $columnIndex = $order['column'];
                    $columnName = 'id';
                    $direction = 'desc';

                    if ($columnIndex != 0) {
                        $columnName = request()->columns[$columnIndex]['data'];
                        $direction = $order['dir'];
                    }

                    $query->orderBy($columnName, $direction);
                }
            })
            ->rawColumns(['status', 'payment_proof', 'action']);
    }

    public function query(TrainerSubscription $model)
    {
        return $model->newQuery()->with(['trainer.user', 'package'])->latest();
    }

    protected function getColumns()
    {
        return [
            Column::make('DT_RowIndex')
                ->searchable(false)
                ->title(__('message.srno'))
                ->orderable(false),
            ['data' => 'trainer_name', 'name' => 'trainer_name', 'title' => __('message.trainer'), 'searchable' => false, 'orderable' => false],
            ['data' => 'trainer_email', 'name' => 'trainer_email', 'title' => __('message.trainer_email'), 'searchable' => false, 'orderable' => false],
            // transaction_reference hidden from table but available in row data
            ['data' => 'transaction_reference', 'name' => 'transaction_reference', 'title' => __('message.transaction_reference'), 'searchable' => false, 'orderable' => false, 'visible' => false],
            ['data' => 'payment_proof', 'name' => 'payment_proof', 'title' => 'Payment Proof', 'searchable' => false, 'orderable' => false],
            ['data' => 'started_at', 'name' => 'started_at', 'title' => __('message.subscription_start_date')],
            ['data' => 'ends_at', 'name' => 'ends_at', 'title' => __('message.subscription_end_date')],
            ['data' => 'status', 'name' => 'status', 'title' => __('message.status')],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => __('message.created_at')],
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->title(__('message.action'))
                ->width(60)
                ->addClass('text-center hide-search'),
        ];
    }
}
