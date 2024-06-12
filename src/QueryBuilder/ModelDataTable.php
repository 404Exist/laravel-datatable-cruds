<?php

namespace Exist404\DatatableCruds\QueryBuilder;

use Exist404\DatatableCruds\Exceptions\ModelIsNotSet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
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
    private string $tableNameWithDBName;
    private string $connectionDriver;

    public function __construct(Builder|string $model, ?string $tableName = null)
    {
        if (!isset($model)) {
            throw ModelIsNotSet::create();
        }
        if (is_string($model)) {
            $model = (new $model())->query();
        }

        $this->setData($model, $tableName);

        $this->fixQueryWheresColumnsNames();
    }

    private function setData(Builder $model, ?string $tableName = null): void
    {
        $this->query = $model;
        $this->model = $model->getModel();
        if ($tableName) {
            $this->model->setTable($tableName);
        }
        $this->connectionDriver = $this->query->getQuery()->getConnection()->getDriverName();
        $this->tableName = $this->model->getTable();
        $this->tableNameWithDBName = $this->tableName($this->query->getQuery(), $this->tableName);
        $this->primaryKeyName = $this->model->getKeyName();
        $this->request = (object) array_merge($this->defaultRequest, $_GET);
        $this->select[] = "{$this->tableName}.*";
    }

    private function tableName(QueryBuilder $query, string $tableName): string
    {
        $driverName = $query->getConnection()->getDriverName();
        $prefix = $driverName == 'sqlsrv' && ! str_contains($tableName, 'dbo') ? ".dbo." : ".";
        if (str_contains($tableName, $query->getConnection()->getDatabaseName())) {
            return $tableName;
        }
        return $query->getConnection()->getDatabaseName() . $prefix . $tableName;
    }

    private function fixQueryWheresColumnsNames(): void
    {
        $this->query->getQuery()->wheres = array_map(
            function ($where) {
                if (isset($where['column']) && ! isset(explode(".", $where['column'])[1])) {
                    $where['column'] = $this->tableNameWithDBName . "." . $where['column'];
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

        $query->getQuery()->from($this->tableName);

        return $query
            ->select(...$this->select)
            ->distinct("{$this->tableNameWithDBName}.{$this->primaryKeyName}")
            ->paginate($this->request->limit, ['*'], 'page', $this->request->page);
    }

    private function applyWithRelations(Builder $query): Builder
    {
        if ($this->query->getQuery()->columns) {
            foreach ($this->query->getQuery()->columns as $column) {
                if ($column instanceof \Illuminate\Database\Query\Expression) {
                    $this->select[] = DB::raw($column->getValue($this->query->getGrammar()));
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
        foreach ($this->request->searchBy as $index => $field) {
            $method = $index == 0 ? "where" : "orWhere";
            if ($this->isRelatedToModel($field)) {
                @list($relation, $column) = $this->listRelationAndColumn($field);
                $query = $query->{$method . "Has"}(
                    $relation,
                    function ($query) use ($column, $relation) {
                        $query->from($this->relatedTableName($relation));
                        return $query->whereRaw(
                            "LOWER({$this->fixColumnName($this->relatedTableName($relation).'.'.$column)}) LIKE (?)",
                            ["%{$this->request->search}%"]
                        );
                    }
                );
            } else {
                $column = $this->fixColumnName("{$this->tableNameWithDBName}.$field");
                $query = $query->{$method . "Raw"}("LOWER($column) LIKE (?)", ["%{$this->request->search}%"]);
            }
        }

        return $query;
    }

    private function applyFilters(Builder $query): Builder
    {
        foreach ($this->request->filterBy as $field => $value) {
            if (!empty($value)) {
                $operator = "=";

                if (preg_match('/^([!<>=]+)(.+)/', $value, $matches)) {
                    $operator = $matches[1] == "!" ? "!=" : $matches[1];
                    $value = $matches[2];
                }
                $value = $value == "null" ? null : $value;

                if ($this->isRelatedToModel($field)) {
                    @list($relation, $column) = $this->listRelationAndColumn($field);
                    $query = $query->whereHas(
                        $relation,
                        fn($query)=> $query->where($column, $operator, $value)
                    );
                } else {
                    $query = $query->where("{$this->tableNameWithDBName}.$field", $operator, $value);
                }
            }
        }

        return $query;
    }

    private function orderByRelated(): Builder
    {
        @list($relationName, $orderBy) = $this->listRelationAndColumn($this->request->orderBy);

        $relatedTableName = $this->relatedTableName($relationName);
        $relatedTableNameWithoutDot = str_replace('.', '_', $relatedTableName);

        $this->select[] = "$relatedTableName.$orderBy as datatableas_$relatedTableNameWithoutDot" . "_" . $orderBy;

        return $this->leftJoinRelation($relationName)
            ->orderBy("datatableas_$relatedTableNameWithoutDot" . "_" . $orderBy, $this->request->order ?? 'desc');
    }

    private function fixColumnName(string $column): string
    {
        $columns = explode("->", $column);

        if (count($columns) > 1) {
            $column = $this->addBackTicksToColumnName(array_shift($columns));

            if ($this->connectionDriver == 'sqlsrv') {
                $sql = "json_value($column, '$";
            } else if ($this->connectionDriver == 'pgsql') {
                $sql = "jsonb_extract_path($column, '";
            } else {
                $sql = "json_unquote(json_extract($column, '$";
            }

            foreach ($columns as $column) {
                $sql .= ".\"$column\"";
            }

            if ($this->connectionDriver == 'mysql') {
                $sql .= "'))";
            } else {
                $sql .= "')";
            }

            return $sql;
        }

        return $this->addBackTicksToColumnName($column);
    }

    private function addBackTicksToColumnName(string $column): string
    {
        $fixedColumn = "";

        foreach (explode('.', $column) as $column) {
            if ($this->connectionDriver == 'mysql') {
                $fixedColumn .= "`$column`.";
            } else if ($this->connectionDriver == 'pgsql') {
                $fixedColumn .= "\"$column\".";
            } else if ($this->connectionDriver == 'sqlsrv') {
                $fixedColumn .= "[$column].";
            } else {
                $fixedColumn .= "$column.";
            }
        }

        return rtrim($fixedColumn, '.');
    }
}
