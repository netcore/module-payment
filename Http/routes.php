<?php

Route::group([
    'prefix'     => 'admin/payment',
    'as'         => 'admin::payment.',
    'middleware' => ['web', 'auth.admin'],
    'namespace'  => 'Modules\Payment\Http\Controllers\Admin',
], function () {
    Route::get('/pagination', [
        'uses' => 'PaymentController@pagination',
        'as'   => 'pagination',
    ]);

    Route::get('/', [
        'uses' => 'PaymentController@index',
        'as'   => 'index',
    ]);

    Route::delete('/{payment}', [
        'uses' => 'PaymentController@destroy',
        'as'   => 'destroy',
    ]);
});