<?php

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

if (!function_exists('dataTableOf')) {
    function dataTableOf($model = null): LengthAwarePaginator|Collection
    {
        return (new \Exist404\DatatableCruds\ModelDataTable($model))->get();
    }
}

if (!function_exists('datatableScriptPath')) {
    function datatableScriptPath(): string|false
    {
        return __DIR__ . '/public/js/datatable-cruds.min.js';
    }
}
