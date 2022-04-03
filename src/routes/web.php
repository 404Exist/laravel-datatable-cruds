<?php

use App\Http\Controllers\DatatableExampleController;
use Exist404\DatatableCruds\Controllers\AssetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/_datatablecrudsminfindjs', [AssetController::class, 'js']);
Route::get('/datatable-cruds-example', [DatatableExampleController::class, 'index']);
Route::post('/datatable-cruds-example', [DatatableExampleController::class, 'store'])->name('datatable-cruds-example.store');
Route::patch('/datatable-cruds-example/{id}', [DatatableExampleController::class, 'update'])->name('datatable-cruds-example.update');
Route::delete('/datatable-cruds-example/{id}', [DatatableExampleController::class, 'delete'])->name('datatable-cruds-example.delete');
