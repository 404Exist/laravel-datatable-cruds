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
Route::redirect('/', '/datatable-cruds-example');
Route::prefix('datatable-cruds-example')->name('datatable-cruds-example')->controller(DatatableExampleController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store')->name('.store');
    Route::patch('/{id}', 'update')->name('.update');
    Route::delete('/{id}', 'delete')->name('.delete');
});
