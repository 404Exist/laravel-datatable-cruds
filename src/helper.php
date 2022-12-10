<?php

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

if (!function_exists('dataTableOf')) {
    function dataTableOf($model = null): LengthAwarePaginator|Collection
    {
        return (new \Exist404\DatatableCruds\ModelDataTable($model))->get();
    }
}
