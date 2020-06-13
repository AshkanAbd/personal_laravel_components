<?php

namespace App\Component\Extensions;

use App\Component\Tools\Builder;
use App\Component\Tools\RouteBindingResolver;
use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;

trait ModelExtension
{
    use SoftDeletes, RouteBindingResolver;

    /**
     * model default columns
     *
     * @var array
     */
    protected $defaultColumns = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * exclude array
     *
     * @var array
     */
    protected $exclude = [

    ];

    protected static function recursiveDelete()
    {
        return function ($model) {
            $model->delete();
        };
    }

    /**
     * @param array $columns
     */
    public function setColumns(array $columns): void
    {
        $this->defaultColumns = $columns;
    }

    /**
     * @param array $columns
     */
    public function addColumns(array $columns): void
    {
        $this->defaultColumns = array_merge($this->defaultColumns, $columns);
    }

    /**
     * Exclude created_at, updated_at, deleted_at from select
     *
     * @param $query
     * @return mixed
     */
    public function scopeHideTimes($query)
    {
        return $query->exclude([
            "created_at",
            "updated_at",
            "deleted_at",
        ]);
    }

    /**
     * For better performance, complete fillable array in model
     *
     * @param $query
     * @param array $value
     * @return mixed
     */
    public function scopeExclude($query, $value = array())
    {
        if (gettype($value) == 'string') {
            $value = [$value];
        }
        $array = array_merge($this->fillable, $this->defaultColumns);
        $this->exclude = array_merge($this->exclude, (array)$value);
        return $query->select(array_reverse(array_diff($array, $this->exclude)));
    }

    /**
     * Don't use this scope.
     * Still in develop faze...
     *
     * @param $query
     * @param array $value
     * @return mixed
     * @throws Exception
     */
    public function scopeSoftDelete($query, array $value = [])
    {
        throw new Exception("Still in develop faze...");
        return $query->where(function ($q) use ($value) {
            foreach ($value as $item) {
                $q->where("$item.deleted_at", NULL);
            }
        });
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }
}