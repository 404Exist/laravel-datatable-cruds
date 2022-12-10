<?php

use Exist404\DatatableCruds\Exceptions\ModelIsNotSet;
use Illuminate\Support\Facades\Route;

Route::get(config('datatablecruds.script_file_url'), function () {
    $sctiptContent = file_get_contents(__DIR__ . '/../public/js/datatable-cruds.min.js');
    return response($sctiptContent, 200, ['Content-Type' => 'text/javascript']);
});
