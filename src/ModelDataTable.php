<?php

namespace Exist404\DatatableCruds;

use Exist404\DatatableCruds\Exceptions\ModelIsNotSet;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Relation;

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
    private Builder $query;
    private string $tableName;
    private string $primaryKeyName;
    private object $request;

    public function __construct(Builder|string $model)
    {
        if (!isset($model)) {
            throw ModelIsNotSet::create();
        }
        if (is_string($model)) {
            $model = (new $model())->query();
        }

        $this->setData($model);

        $this->fixQueryWheresColumnsNames();
    }

    public function get(): LengthAwarePaginator
    {
        $query = $this->applyOrderRequest();

        $query = $this->applyWithRelations($query);

        if ($this->request->search && $this->request->search != '') {
            $query = $this->applySearch($query);
        }
        return $query->select("{$this->tableName}.*")
                    ->paginate($this->request->limit);
    }

    private function setData(Builder $model): void
    {
        $this->query = $model;
        $this->model = $model->getModel();
        $this->tableName = $this->model->getTable();
        $this->primaryKeyName = $this->model->getKeyName();
        $this->request = (object) array_merge($this->defaultRequest, $_GET);
    }

    private function fixQueryWheresColumnsNames(): void
    {
        $this->query->getQuery()->wheres = array_map(
            function ($where) {
                if (! isset(explode(".", $where['column'])[1])) {
                    $where['column'] = $this->tableName . "." . $where['column'];
                }
                return $where;
            },
            $this->query->getQuery()->wheres
        );
    }

    private function applyOrderRequest(): Builder
    {
        if ($this->isRelatedToModel($this->request->orderBy)) {
            return $this->orderByRelated();
        }
        return $this->query->orderBy($this->request->orderBy, $this->request->order);
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
            $method = $index == 0 ? "where" : "orWhere";
            if ($this->isRelatedToModel($field)) {
                @list($relation, $column) = $this->listRelationAndColumn($field);
                $query = $query->{$method . "Has"}(
                    $relation,
                    fn($query)=> $query->where($column, 'like', "%{$this->request->search}%")
                );
            } else {
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
        @list($relationName, $orderBy) = $this->listRelationAndColumn($this->request->orderBy);

        $relatedTableName = $this->relatedTableName($relationName);

        return $this->leftJoinRelation($relationName)
            ->orderBy("$relatedTableName.$orderBy", $this->request->order ?? 'desc');
    }

    private function leftJoinRelation(string $relationName): Builder
    {
        return $this->query->distinct("{$this->tableName}.{$this->primaryKeyName}")
            ->leftJoin($this->relatedTableName($relationName), function ($join) use ($relationName) {
                $relation = $this->relatedRelation($relationName);
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

    private function listRelationAndColumn(string $field)
    {
        $fields = explode('.', $field);
        $column = $fields[count($fields) - 1];
        $relations = "";
        array_pop($fields);
        foreach ($fields as $relation) {
            $relations .= ".$relation";
        }
        $relations = ltrim($relations, ".");
        return [
            $relations,
            $column
        ];
    }

    private function relatedRelation(string $relationName): Relation
    {
        $relationNames = explode('.', $relationName);
        $relatedModel = $this->model;
        $relation = $relationName;
        if (count($relationNames) > 1) {
            $relation = array_pop($relationNames);

            foreach ($relationNames as $relationName) {
                $relatedModel = $relatedModel->$relationName()->getModel();
            }
        }

        return $relatedModel->$relation();
    }

    private function relatedModel(string $relationName): Model
    {
        return $this->relatedRelation($relationName)->getModel();
    }

    private function relatedTableName(string $relationName): string
    {
        return $this->relatedModel($relationName)->getTable();
    }
}
