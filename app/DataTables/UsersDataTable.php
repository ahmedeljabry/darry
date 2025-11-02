<?php
declare(strict_types=1);

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('role', function(User $u){ return $u->roles->pluck('name')->implode(', '); })
            ->addColumn('property', function (User $u) {
                if ($u->property) {
                    return $u->property->name;
                }
                return __('users.scope_system_short') ?? __('users.scope_system');
            })
            ->editColumn('status', fn(User $u) => $u->status === 'ACTIVE' ? __('owners.active') : __('owners.inactive'))
            ->editColumn('created_at', fn(User $u) => optional($u->created_at)->format('Y-m-d'))
            ->addColumn('actions', function (User $u) {
                return view('admin.layouts.partials._actions', [
                    'showRoute' => null,
                    'editRoute' => route('admin.users.edit', $u->id),
                    'deleteRoute' => route('admin.users.destroy', $u->id),
                ])->render();
            })
            ->rawColumns(['actions'])
            ->setRowId('id');
    }

    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['roles', 'property'])
            ->when(auth()->user()?->property_id, fn ($query, $propertyId) => $query->where('property_id', $propertyId))
            ->select(['id','property_id','name','email','phone','status','created_at']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('users-table')
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
            ->buttons([Button::make('excel'), Button::make('csv'), Button::make('pdf'), Button::make('print')]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false),
            Column::make('name')->title(__('menu.users')),
            Column::make('email')->title(__('auth.email') ?? 'البريد الإلكتروني'),
            Column::make('phone')->title(__('tenants.phone')),
            Column::computed('property')->title(__('units.property')),
            Column::make('status')->title(__('owners.status')),
            Column::make('role')->title(__('roles.roles') ?? 'الدور'),
            Column::make('created_at')->title(__('messages.created_at')),
            Column::computed('actions')->title(__('messages.actions'))->exportable(false)->printable(false)->width(120)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}
