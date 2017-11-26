<?php

namespace Modules\Payment\Modules;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'netcore_payment__payments';

    protected $fillable = ['user_id', 'amount', 'state', 'status', 'method'];
}
