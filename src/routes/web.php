<?php
use Exist404\DatatableCruds\Controllers\AssetController;
use Illuminate\Support\Facades\Route;
Route::get('/_datatablecrudsminfindjs', [AssetController::class, 'js']);
