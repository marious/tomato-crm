<?php

namespace TomatoPHP\TomatoCrm\Tables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;
use TomatoPHP\TomatoCategory\Models\Type;

class AccountTable extends AbstractTable
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(
        public ?Builder $query
    )
    {
       if(!$this->query){
           $this->query = \TomatoPHP\TomatoCrm\Models\Account::query();
       }
    }

    /**
     * Determine if the user is authorized to perform bulk actions and exports.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return true;
    }

    /**
     * The resource or query builder.
     *
     * @return mixed
     */
    public function for()
    {
        return $this->query;
    }

    /**
     * Configure the given SpladeTable.
     *
     * @param \ProtoneMedia\Splade\SpladeTable $table
     * @return void
     */
    public function configure(SpladeTable $table)
    {

        $table
            ->withGlobalSearch(label: trans('tomato-admin::global.search'),columns: ['id','name','username','email', 'phone'])
            ->bulkAction(
                label: trans('tomato-admin::global.crud.delete'),
                each: fn (\TomatoPHP\TomatoCrm\Models\Account $model) => $model->delete(),
                after: fn () => Toast::danger(__('Account Has Been Deleted'))->autoDismiss(2),
                confirm: true
            )
            ->export()
            ->selectFilter('type_id',
                options:Type::where('for', 'accounts')->get()->pluck('name', 'id')->toArray(),
                label: __('Type'))
            ->defaultSort('id')
            ->column(
                key: 'name',
                label: __('Name'),
                sortable: true)
            ->column(
                key: 'username',
                label: __('Username'),
                sortable: true)
            ->column(
                key: 'last_login',
                label: __('Last login'),
                sortable: true)
            ->column(
                key: 'is_active',
                label: __('Activated'),
                sortable: true);


        foreach (\TomatoPHP\TomatoCrm\Facades\TomatoCrm::getTableCols() as $key=>$item){
            $table->column(
                key: $key,
                label: $item,
                sortable: false,
            );
        }

        $table->column(key: 'actions',label: trans('tomato-admin::global.crud.actions'))
        ->paginate(15);
    }
}
