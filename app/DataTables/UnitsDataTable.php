<?php

declare(strict_types=1);

namespace App\DataTables;

use App\Models\Unit;
use App\Domain\Enums\UnitStatus;
use App\Domain\Enums\UnitType;
use App\Domain\Enums\RentType;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UnitsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('property', fn (Unit $u) => $u->property?->name ?? '--')
            ->editColumn('rent_amount', fn(Unit $u) => number_format((float) $u->rent_amount, 2))
            ->editColumn('unit_type', function (Unit $u) {
                $type = $u->unit_type instanceof UnitType ? $u->unit_type : UnitType::from((string) $u->unit_type);
                return __('units.types.' . $type->value);
            })
            ->editColumn('rent_type', function (Unit $u) {
                $rent = $u->rent_type instanceof RentType ? $u->rent_type : RentType::from((string) $u->rent_type);

                [$dotClass, $textClass] = match ($rent) {
                    RentType::DAILY            => ['label-primary', 'text-primary'],
                    RentType::MONTHLY          => ['label-info',    'text-info'],
                    RentType::DAILY_OR_MONTHLY => ['label-warning', 'text-warning'],
                    default                    => ['label-secondary', 'text-muted'],
                };

                $label = __('units.rent_types.' . $rent->value);

                return sprintf(
                    '<span class="label %s label-dot mr-2"></span><span class="font-weight-bold %s">%s</span>',
                    e($dotClass),
                    e($textClass),
                    e($label)
                );
            })
            ->editColumn('status', function (Unit $u) {
                $status = $u->status instanceof UnitStatus ? $u->status : UnitStatus::from((string) $u->status);
                $badge = match ($status) {
                    UnitStatus::ACTIVE => 'label-success',
                    UnitStatus::INACTIVE => 'label-danger',
                };

                $label = __('units.statuses.' . $status->value);
                return "<span class=\"label label-lg font-weight-bold label-inline {$badge}\">{$label}</span>";
            })
            ->editColumn('actions', function (Unit $u) {
                return view('admin.layouts.partials._actions', ['editRoute' => route('admin.units.edit' , $u->id) , 'deleteRoute' => route('admin.units.destroy' , $u->id) , 'showRoute' => route('admin.units.show', $u->id) ])->render();
            })
            ->rawColumns(['status', 'actions','rent_type'])
            ->setRowId('id');
    }

    public function query(Unit $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('property:id,name')
            ->select([
                'id',
                'property_id',
                'name',
                'unit_type',
                'capacity',
                'rent_type',
                'rent_amount',
                'status',
            ]);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('units-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addTableClass('table table-row-dashed table-hover align-middle gy-4')
            ->orderBy(1)
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'stateSave' => true,
                'pageLength' => 10,
                'lengthMenu' => [[10, 25, 50, -1], [10, 25, 50, __('messages.all') ?? 'الكل']],
                'destroy' => true,
            ])
            ->buttons(['excel','csv','pdf','print']);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
                ->title('#')
                ->searchable(false)
                ->orderable(false),

            Column::make('name')->title(__('units.name')),
            Column::computed('property')->title(__('units.property')),
            Column::make('unit_type')->title(__('units.unit_type')),
            Column::make('capacity')->title(__('units.capacity')),
            Column::make('rent_amount')->title(__('units.rent_amount')),
            Column::make('rent_type')->title(__('units.rent_type')),

            Column::computed('status')
                ->title(__('units.status'))
                ->exportable(true)
                ->printable(true)
                ->addClass('text-center'),

            Column::computed('actions')
                ->title(__('units.actions'))
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Units_' . date('YmdHis');
    }
}
