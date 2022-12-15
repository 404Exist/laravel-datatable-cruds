<?php

namespace Exist404\DatatableCruds;

use Exist404\DatatableCruds\Exceptions\ModelIsNotSet;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModelDataTable
{
    private array $defaultRequest = [
        'orderBy' => 'created_at',
        'order' => 'desc',
        'page' => 1,
        'limit' => 10,
        'with' => null,
        'search' => null,
        'searchBy' => [],
    ];

    private Model $model;
    private string $tableName;
    private string $primaryKeyName;
    private object $request;

    public function __construct(string $model)
    {
        if (!isset($model)) {
            throw ModelIsNotSet::create();
        }
        $this->request = (object) array_merge($this->defaultRequest, $_GET);
        $this->model = new $model();
        $this->tableName = $this->model->getTable();
        $this->primaryKeyName = $this->model->getKeyName();
    }

    public function get(): LengthAwarePaginator|Collection
    {
        $query = $this->applyOrderRequest();

        $query = $this->applyWithRelations($query);

        if ($this->request->search && $this->request->search != '') {
            $query = $this->applySearch($query);

            if (!isset($this->request->limit)) {
                return $query->select("{$this->tableName}.*")->get();
            }
        }
        return $query->select("{$this->tableName}.*")
                    ->paginate($this->request->limit, ['*'], 'page', $this->request->page);
    }

    private function applyOrderRequest(): Builder
    {
        if ($this->isRelatedToModel($this->request->orderBy)) {
            return $this->orderByRelated();
        }
        return $this->model->orderBy($this->request->orderBy, $this->request->order);
    }

    private function applyWithRelations(Builder $query): Builder
    {
        if ($this->request->with) {
            $with = !is_array($this->request->with) ? explode('|', $this->request->with) : $this->request->with;
            foreach ($with as $related) {
                $query = $query->with($related);
            }
        }
        return $query;
    }

    private function applySearch(Builder $query): Builder
    {
        $searchBy = $this->request->searchBy ?: [];
        $searchBy = !is_array($searchBy) ? explode(',', $searchBy) : $searchBy;
        foreach ($searchBy as $index => $field) {
            if ($this->isRelatedToModel($field)) {
                @list($relation, $column) = explode('.', $field);
                $method = $index == 0 ? "whereHas" : "orWhereHas";
                $query = $query->$method(
                    $relation,
                    fn($query)=> $query->where($column, 'like', "%{$this->request->search}%")
                );
            } else {
                $method = $index == 0 ? "where" : "orWhere";
                $query = $query->$method("{$this->tableName}.$field", 'like', "%{$this->request->search}%");
            }
        }
        return $query;
    }

    private function isRelatedToModel(string $field): bool
    {
        return isset(explode('.', $field)[1]);
    }

    private function orderByRelated(): Builder
    {
        @list($relationName, $orderBy) = explode('.', $this->request->orderBy);

        $relatedTableName = $this->relatedTableName($relationName);

        return $this->leftJoinRelation($relationName)
            ->orderBy("$relatedTableName.$orderBy", $this->request->order ?? 'desc');
    }

    private function leftJoinRelation(string $relationName): Builder
    {
        return $this->model->distinct("{$this->tableName}.{$this->primaryKeyName}")
            ->leftJoin($this->relatedTableName($relationName), function ($join) use ($relationName) {
                $relation = $this->model->$relationName();
                $relatedModel = $this->relatedModel($relationName);
                $relatedForeign = $relation->getForeignKeyName();
                $relatedTableName = $relatedModel->getTable();
                $relatedOwnerKey = $relatedModel->getKeyName();
                $relatedMorphType = method_exists($relation, "getMorphType") ? $relation->getMorphType() : null;
                $relatedMorphClass = method_exists($relation, "getMorphClass") ? $relation->getMorphClass() : null;
                if ($relatedMorphClass) {
                    $join->on("$relatedTableName.$relatedMorphType", '=', $relatedMorphClass);
                }
                if ($relation instanceof BelongsTo) {
                    $join->on("{$this->tableName}.$relatedForeign", '=', "$relatedTableName.$relatedOwnerKey");
                } else {
                    $join->on("$relatedTableName.$relatedForeign", '=', "{$this->tableName}.$relatedOwnerKey");
                }
            });
    }

    private function relatedModel(string $relationName): Model
    {
        return $this->model->$relationName()->getModel();
    }

    private function relatedTableName(string $relationName): string
    {
        return $this->relatedModel($relationName)->getTable();
    }
}
