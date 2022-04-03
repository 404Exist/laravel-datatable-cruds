<?php
if (!function_exists('dataTableOf')) {
    function dataTableOf($model = null)
    {
        $defaultRequest = [
            'orderBy' => 'created_at',
            'order' => 'asc',
            'page' => 1,
            'with' => null,
            'search' => null,
            'searchBy' => ['name'],
        ];
        if (!$model) return throw new \Exception('Please provide model first');
        $model = new $model;
        $request = (object) array_merge($defaultRequest, $_GET);
        function isRelatedToModel($field) {
            return isset(explode('.', $field)[1]);
        }
        function orderByRelated($model, $request){
            $tableName = $model->getTable();
            $related = explode('.', $request->orderBy)[0];
            $orderBy = explode('.', $request->orderBy)[1];
            $relatedModel = $model->$related()->getRelated();
            $relatedTableName = $relatedModel->getTable();
            $relatedForeign = $relatedModel->getForeignKey();
            $relatedOwnerKey = $relatedModel->getKeyName();
            return $model->join(
                $relatedTableName, $tableName.'.'.$relatedForeign, '=', $relatedTableName.'.'.$relatedOwnerKey
            )->orderBy($relatedTableName.'.'.$orderBy, $request->order ?? 'desc');
        }

        if (isRelatedToModel($request->orderBy)) $query = orderByRelated($model, $request);
        else $query = $model->orderBy($request->orderBy ?? 'created_at', $request->order ?? 'desc');

        if ($request->with) {
            $with = !is_array($request->with) ? explode('|', $request->with) : $request->with;
            foreach ($with as $related) {
                $query = $query->with($related);
            }
        }
        if ($request->search && $request->search != '') {
            $searchBy = $request->searchBy ? $request->searchBy : [];
            $searchBy = !is_array($searchBy) ? explode(',', $searchBy) : $searchBy;
            foreach ($searchBy as $index => $field) {
                if (isRelatedToModel($field)) {
                    $relation = explode('.', $field)[0];
                    $column = explode('.', $field)[1];
                    $query = $index == 0 ? $query->whereHas($relation, function ($query) use ($column, $request) {
                        $query->where($column, 'like', '%'.$request->search.'%');
                    }) :
                        $query->orWhereHas($relation, function ($query) use ($column, $request) {
                            $query->where($column, 'like', '%'.$request->search.'%');
                        })
                    ;
                } else {
                    $field = $model->getTable().'.'.$field;
                    $query = $index == 0 ? $query->where($field, 'like', '%'.$request->search.'%') : $query->orWhere($field, 'like', '%'.$request->search.'%');
                }
            }
            if (!isset($request->limit)) return $query->select($model->getTable().'.*')->get();
        }
        return $query->select($model->getTable().'.*')->paginate($request->limit, ['*'], 'page', $request->page);
    }
}
