<?php
declare(strict_types=1);

namespace App\DataTables;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExpensesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('property', fn (Expense $e) => e($e->property->name ?? '—'))
            ->addColumn('unit', fn (Expense $e) => e($e->unit->name ?? '—'))
            ->editColumn('amount', fn (Expense $e) => number_format((float) $e->amount, 3))
            ->addColumn('actions', function (Expense $e) {
                return view('admin.layouts.partials._actions', [
                    'showRoute' => null,
                    'editRoute' => route('admin.expenses.edit', $e->id),
                    'deleteRoute' => route('admin.expenses.destroy', $e->id),
                ])->render();
            })
            ->rawColumns(['actions'])
            ->setRowId('id');
    }

    public function query(Expense $model): QueryBuilder
    {
        return $model->newQuery()->with(['property','unit'])
            ->select(['id','property_id','unit_id','title','date','amount','receipt_no','category']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('expenses-table')
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
            ])
            ->buttons([
                Button::make('excel'), Button::make('csv'), Button::make('pdf'), Button::make('print')
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false),
            Column::make('title')->title(__('expenses.title')),
            Column::make('date')->title(__('expenses.date')),
            Column::make('amount')->title(__('expenses.amount')),
            Column::make('receipt_no')->title(__('expenses.receipt_no')),
            Column::make('property')->title(__('expenses.property')),
            Column::make('unit')->title(__('expenses.unit')),
            Column::make('category')->title(__('expenses.category')),
            Column::computed('actions')->title(__('messages.actions'))->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Expenses_' . date('YmdHis');
    }
}
