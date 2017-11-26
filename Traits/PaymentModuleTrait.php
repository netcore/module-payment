<?php

namespace Modules\Payment\Traits;

use Modules\Payment\Modules\Payment;

trait PaymentModuleTrait {

    /**
     * @return mixed
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
