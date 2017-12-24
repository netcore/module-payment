<?php

namespace Modules\Payment\Modules;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;
use Modules\Invoice\Models\Invoice;

class Payment extends Model
{

    /**
     *
     */
    const STATE_OPTIONS = [
        'successful' => 'Successful',
        'failed'     => 'Failed',
        'in_process' => 'In process',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'netcore_payment__payments';

    /**
     * Mass assignable
     *
     * @var array
     */
    protected $fillable = ['user_id', 'amount', 'state', 'status', 'method', 'is_active', 'data'];

    /**
     * Eager loading
     *
     * @var array
     */
    protected $with = ['invoice'];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
