<?php

use Illuminate\Routing\Router;

/** @var Router $router */
Route::prefix('/statuses')->group(function (Router $router) {
    $router->post('/order-status', [
        'as' => 'statuses.order.status',
        'uses' => 'StatusApiController@updateOrderStatus',
        'middleware' => ['auth:api', 'auth-can:requestable.statuses.edit'],
    ]);
});
