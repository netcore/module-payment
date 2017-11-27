<?php

Route::group([
    'prefix'     => 'admin/payment',
    'as'         => 'admin::payment.',
    'middleware' => ['web', 'auth.admin'],
    'namespace'  => 'Modules\Payment\Http\Controllers\Admin'
], function () {
    Route::get('/', [
        'uses' => 'PaymentController@index',
        'as'   => 'index'
    ]);

    Route::get('/pagination', [
        'uses' => 'PaymentController@pagination',
        'as'   => 'pagination'
    ]);
});