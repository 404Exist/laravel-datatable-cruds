<?php

namespace Exist404\DatatableCruds;

use Exist404\DatatableCruds\Exceptions\ModelIsNotSet;

class ModelDataTable
{
    private $defaultRequest = [
        'orderBy' => 'created_at',
        'order' => 'desc',
        'page' => 1,
        'limit' => 10,
        'with' => null,
        'search' => null,
        'searchBy' => [],
    ];

    private $model;
    private $tableName;
    private $request;

    public function __construct($model)
    {
        if (!isset($model)) {
            throw ModelIsNotSet::create();
        }
        $this->request = (object) array_merge($this->defaultRequest, $_GET);
        $this->model = new $model();
        $this->tableName = $this->model->getTable();
    }
    public function get()
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

    private function applyOrderRequest()
    {
        if ($this->isRelatedToModel($this->request->orderBy)) {
            return $this->orderByRelated();
        }
        return $this->model->orderBy($this->request->orderBy, $this->request->order);
    }

    private function applyWithRelations($query)
    {
        if ($this->request->with) {
            $with = !is_array($this->request->with) ? explode('|', $this->request->with) : $this->request->with;
            foreach ($with as $related) {
                $query = $query->with($related);
            }
        }
        return $query;
    }

    private function applySearch($query)
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

    private function isRelatedToModel($field)
    {
        return isset(explode('.', $field)[1]);
    }

    private function orderByRelated()
    {
        @list($related, $orderBy) = explode('.', $this->request->orderBy);
        $relatedModel = $this->model->$related()->getRelated();
        $relatedTableName = $relatedModel->getTable();
        $relatedForeign = $relatedModel->getForeignKey();
        $relatedOwnerKey = $relatedModel->getKeyName();
        return $this->model->join(
            $relatedTableName,
            "{$this->tableName}.$relatedForeign",
            '=',
            "$relatedTableName.$relatedOwnerKey"
        )->orderBy("$relatedTableName.$orderBy", $this->request->order ?? 'desc');
    }
}
