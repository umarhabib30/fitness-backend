<?php

namespace App\DataTables;

use App\Models\TrainerPackage;
use App\Traits\DataTableTrait;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TrainerPackageDataTable extends DataTable
{
    use DataTableTrait;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('price', function ($trainerPackage) {
                return getPriceFormat($trainerPackage->price);
            })
            ->editColumn('interval', function ($trainerPackage) {
                return match ($trainerPackage->interval) {
                    'monthly' => __('message.monthly'),
                    'yearly' => __('message.yearly'),
                    default => $trainerPackage->interval,
                };
            })
            ->editColumn('is_active', function ($trainerPackage) {
                $status = $trainerPackage->is_active ? 'primary' : 'warning';
                $label = $trainerPackage->is_active ? __('message.active') : __('message.inactive');

                return '<span class="text-capitalize badge bg-' . $status . '">' . $label . '</span>';
            })
            ->editColumn('created_at', function ($trainerPackage) {
                return dateAgoFormate($trainerPackage->created_at, true);
            })
            ->editColumn('updated_at', function ($trainerPackage) {
                return dateAgoFormate($trainerPackage->updated_at, true);
            })
            ->addColumn('duration_label', function ($trainerPackage) {
                $unit = $trainerPackage->interval === 'yearly' ? 'Year(s)' : 'Month(s)';
                return $trainerPackage->duration_days . ' ' . $unit;
            })
            ->addColumn('action', function ($trainerPackage) {
                $id = $trainerPackage->id;

                return view('admin.trainer_packages.action', compact('trainerPackage', 'id'))->render();
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
            ->rawColumns(['action', 'is_active']);
    }

    public function query(TrainerPackage $model)
    {
        return $model->newQuery();
    }

    protected function getColumns()
    {
        return [
            Column::make('DT_RowIndex')
                ->searchable(false)
                ->title(__('message.srno'))
                ->orderable(false),
            ['data' => 'name', 'name' => 'name', 'title' => __('message.name')],
            ['data' => 'price', 'name' => 'price', 'title' => __('message.price')],
            ['data' => 'interval', 'name' => 'interval', 'title' => __('message.duration_unit')],
            ['data' => 'duration_label', 'name' => 'duration_label', 'title' => __('message.duration'), 'searchable' => false, 'orderable' => false],
            ['data' => 'is_active', 'name' => 'is_active', 'title' => __('message.status')],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => __('message.created_at')],
            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => __('message.updated_at')],
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->title(__('message.action'))
                ->width(60)
                ->addClass('text-center hide-search'),
        ];
    }
}
