<?php

Route::group(['middleware' => 'web', 'prefix' => 'payment', 'namespace' => 'Modules\Payment\Http\Controllers'], function()
{
    Route::get('/', 'PaymentController@index');
});
