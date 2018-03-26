<?php

namespace Modules\Payment\Modules;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Invoice\Models\Invoice;

class Payment extends Model
{
    /**
     * Payment states.
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'amount',
        'state',
        'status',
        'method',
        'is_active',
        'data',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'invoice',
    ];

    /** -------------------- Relations -------------------- */

    /**
     * Payment belongs to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            config('netcore.module-admin.user.model', \App\Models\User::class)
        );
    }

    /**
     * Payment belongs to the invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /** -------------------- Helpers -------------------- */

    /**
     * Determine if payment can be deleted from admin panel.
     *
     * @return bool
     */
    public function isDeletable(): bool
    {
        return $this->state != 'successful';
    }
}
