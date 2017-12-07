<?php

namespace Modules\Payment\Modules;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Payment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'netcore_payment__payments';

    protected $fillable = ['user_id', 'amount', 'state', 'status', 'method', 'is_active', 'data'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
