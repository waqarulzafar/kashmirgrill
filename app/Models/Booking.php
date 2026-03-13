<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    public const TYPE_TABLE = 'table';

    public const TYPE_EVENT = 'event';

    public const STATUS_PENDING = 'pending';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_CANCELLED = 'cancelled';

    public const PAYMENT_METHOD_PAY_ON_ARRIVAL = 'pay_on_arrival';

    public const PAYMENT_METHOD_CARD_ON_CONFIRMATION = 'card_on_confirmation';

    public const PAYMENT_STATUS_PENDING = 'pending';

    public const PAYMENT_STATUS_PAID = 'paid';

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'phone_country_iso2',
        'booking_type',
        'status',
        'date',
        'time',
        'dine_in_slot_id',
        'persons',
        'table_preference',
        'selected_menu',
        'special_occasion',
        'payment_method',
        'payment_status',
        'marketing_opt_in',
        'additional_notes',
    ];

    protected $casts = [
        'date' => 'date',
        'marketing_opt_in' => 'boolean',
    ];

    public function dineInSlot(): BelongsTo
    {
        return $this->belongsTo(DineInSlot::class);
    }
}
