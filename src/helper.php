<?php

use Exist404\DatatableCruds\DatatableCruds;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

if (!function_exists('datatableCruds')) {
    function datatableCruds(): DatatableCruds
    {
        return new DatatableCruds();
    }
}

if (!function_exists('dataTableOf')) {
    function dataTableOf(Builder|string $model = null): LengthAwarePaginator
    {
        return (new \Exist404\DatatableCruds\QueryBuilder\ModelDataTable($model))->get();
    }
}

if (!function_exists('datatableScriptPath')) {
    function datatableScriptPath(): string|false
    {
        return __DIR__ . '/public/js/datatable-cruds.min.js';
    }
}
