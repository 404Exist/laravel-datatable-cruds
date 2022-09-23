<?php

if (!function_exists('dataTableOf')) {
    function dataTableOf($model = null)
    {
        return (new \Exist404\DatatableCruds\ModelDataTable($model))->get();
    }
}
