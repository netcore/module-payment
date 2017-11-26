<?php

if (!function_exists('payment')) {
    /**
     * @return \Illuminate\Foundation\Application
     */
    function payment()
    {
        return app('payment');
    }
}