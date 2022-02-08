<?php

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

JsonApiRoute::server('v1')->name('api.v1.')->prefix('v1')->resources(function (ResourceRegistrar $server): void {
    $server->resource('company', JsonApiController::class)->names(['index' => 'company.list', 'show' => 'company.read', 'store' => 'company.create', 'update' => 'company.update', 'destroy' => 'company.delete']);
});
