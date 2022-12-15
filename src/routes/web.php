<?php

use Illuminate\Support\Facades\Route;

Route::get(config('datatablecruds.script_file_url'), function () {
    $scriptContent = file_get_contents(datatableScriptPath());
    $response = response($scriptContent, 200, ['Content-Type' => 'text/javascript']);
    $response->setSharedMaxAge(31536000);
    $response->setMaxAge(31536000);
    $response->setExpires(new \DateTime("+1 year"));
    return $response;
})->name('datatablecruds.script_file_url');
