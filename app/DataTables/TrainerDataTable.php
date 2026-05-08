<?php

namespace App\DataTables;

use App\Models\Trainer;
use App\Traits\DataTableTrait;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TrainerDataTable extends DataTable
{
    use DataTableTrait;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('status', function ($query) {
                $status = 'warning';

                switch ($query->status) {
                    case 'active':
                        $status = 'primary';
                        break;
                    case 'inactive':
                        $status = 'danger';
                        break;
                    case 'suspended':
                        $status = 'dark';
                        break;
                }

                return '<span class="text-capitalize badge bg-' . $status . '">' . $query->status . '</span>';
            })
            ->order(function ($query) {
                if (request()->has('order')) {
                    $order = request()->order[0];
                    $column_index = $order['column'];

                    $column_name = 'id';
                    $direction = 'desc';
                    if ($column_index != 0) {
                        $column_name = request()->columns[$column_index]['data'];
                        $direction = $order['dir'];
                    }

                    $query->orderBy($column_name, $direction);
                }
            })
            ->addColumn('action', 'admin.trainers.action')
            ->rawColumns(['action', 'status']);
    }

    public function query()
    {
        $model = Trainer::query();

        return $this->applyScopes($model);
    }

    protected function getColumns()
    {
        return [
            ['data' => 'name', 'name' => 'name', 'title' => __('message.name')],
            ['data' => 'phone_number', 'name' => 'phone_number', 'title' => __('message.phone_number')],
            ['data' => 'email', 'name' => 'email', 'title' => __('message.email')],
            ['data' => 'status', 'name' => 'status', 'title' => __('message.status')],
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->title(__('message.action'))
                ->searchable(false)
                ->width(60)
                ->addClass('text-center hide-search'),
        ];
    }
}
