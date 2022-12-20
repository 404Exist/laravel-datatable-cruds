<?php

namespace Exist404\DatatableCruds\QueryBuilder;

use Exist404\DatatableCruds\Exceptions\ModelIsNotSet;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ModelDataTable
{
    use HasRelation;

    private array $defaultRequest = [
        'orderBy' => 'created_at',
        'order' => 'desc',
        'page' => 1,
        'limit' => 10,
        'with' => null,
        'search' => null,
        'searchBy' => [],
        'filterBy' => [],
    ];

    private Model $model;
    private Builder $query;
    private string $tableName;
    private string $primaryKeyName;
    private object $request;
    private array $select = [];

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

    private function setData(Builder $model): void
    {
        $this->query = $model;
        $this->model = $model->getModel();
        $this->tableName = $this->model->getTable();
        $this->primaryKeyName = $this->model->getKeyName();
        $this->request = (object) array_merge($this->defaultRequest, $_GET);
        $this->select[] = "{$this->tableName}.*";
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

    public function get(): LengthAwarePaginator
    {
        $query = $this->applyOrderRequest();

        $query = $this->applyWithRelations($query);

        if ($this->request->search && $this->request->search != '') {
            $query = $this->applySearch($query);
        }

        if ($this->request->filterBy) {
            $query = $this->applyFilters($query);
        }

        return $query
            ->select(...$this->select)
            ->distinct("{$this->tableName}.{$this->primaryKeyName}")
            ->paginate($this->request->limit, ['*'], 'page', $this->request->page);
    }

    private function applyWithRelations(Builder $query): Builder
    {
        if ($this->query->getQuery()->columns) {
            foreach ($this->query->getQuery()->columns as $column) {
                if ($column instanceof \Illuminate\Database\Query\Expression) {
                    $this->select[] = DB::raw($column->getValue());
                }
            }
        }

        if ($this->request->with) {
            $with = !is_array($this->request->with) ? explode('|', $this->request->with) : $this->request->with;
            foreach ($with as $related) {
                $query = $query->with($related);
            }
        }
        return $query;
    }

    private function applyOrderRequest(): Builder
    {
        if ($this->isRelatedToModel($this->request->orderBy)) {
            return $this->orderByRelated();
        }
        return $this->query->orderBy("{$this->request->orderBy}", $this->request->order);
    }

    private function applySearch(Builder $query): Builder
    {
        $this->request->page = 1;
        $this->request->search = strtolower($this->request->search);

        foreach ($this->request->searchBy as $index => $field) {
            $method = $index == 0 ? "where" : "orWhere";
            if ($this->isRelatedToModel($field)) {
                @list($relation, $column) = $this->listRelationAndColumn($field);
                $query = $query->{$method . "Has"}(
                    $relation,
                    fn($query)=> $query->whereRaw(
                        "LOWER({$this->fixColumnName($column)}) LIKE (?)",
                        ["%{$this->request->search}%"]
                    )
                );
            } else {
                $column = $this->fixColumnName("{$this->tableName}.$field");
                $query = $query->{$method . "Raw"}("LOWER($column) LIKE (?)", ["%{$this->request->search}%"]);
            }
        }

        return $query;
    }

    private function applyFilters(Builder $query): Builder
    {
        $this->request->page = 1;

        foreach ($this->request->filterBy as $field => $value) {
            if (!empty($value)) {
                if ($this->isRelatedToModel($field)) {
                    @list($relation, $column) = $this->listRelationAndColumn($field);
                    $query = $query->whereHas(
                        $relation,
                        fn($query)=> $query->where($column, $value)
                    );
                } else {
                    $query = $query->where("{$this->tableName}.$field", $value);
                }
            }
        }

        return $query;
    }

    private function orderByRelated(): Builder
    {
        @list($relationName, $orderBy) = $this->listRelationAndColumn($this->request->orderBy);

        $relatedTableName = $this->relatedTableName($relationName);

        $this->select[] = "$relatedTableName.$orderBy as datatableas_$relatedTableName" . "_" . $orderBy;

        return $this->leftJoinRelation($relationName)
            ->orderBy("datatableas_$relatedTableName" . "_" . "$orderBy", $this->request->order ?? 'desc');
    }

    private function fixColumnName(string $column): string
    {
        $columns = explode("->", $column);

        if (count($columns) > 1) {
            $column = $this->addBackTicksToColumnName(array_shift($columns));

            $sql = "json_unquote(json_extract($column, '$";

            foreach ($columns as $column) {
                $sql .= ".\"$column\"";
            }

            $sql .= "'))";

            return $sql;
        }

        return $this->addBackTicksToColumnName($column);
    }

    private function addBackTicksToColumnName(string $column): string
    {
        $fixedColumn = "";

        foreach (explode('.', $column) as $column) {
            $fixedColumn .= "`$column`.";
        }

        return rtrim($fixedColumn, '.');
    }
}
