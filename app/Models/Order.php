<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING_PAYMENT = 'pending_payment';

    public const STATUS_PENDING = 'pending';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_PAYMENT_FAILED = 'payment_failed';

    public const FULFILLMENT_TAKEAWAY = 'takeaway';

    public const FULFILLMENT_DELIVERY = 'delivery';

    public const FULFILLMENT_DINE_IN = 'dine_in';

    public const PAYMENT_METHOD_STRIPE = 'stripe';

    public const PAYMENT_METHOD_PAYPAL = 'paypal';

    public const PAYMENT_STATUS_PENDING = 'pending';

    public const PAYMENT_STATUS_PAID = 'paid';

    public const PAYMENT_STATUS_FAILED = 'failed';

    public const PAYMENT_STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'reference',
        'user_id',
        'status',
        'fulfillment_type',
        'customer_name',
        'customer_email',
        'customer_phone',
        'delivery_address',
        'notes',
        'dine_in_slot_id',
        'reservation_date',
        'reservation_time',
        'guest_count',
        'subtotal',
        'delivery_fee',
        'total',
        'payment_method',
        'payment_provider',
        'payment_status',
        'payment_session_id',
        'payment_reference',
        'payment_meta',
        'paid_at',
        'placed_at',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'payment_meta' => 'array',
        'paid_at' => 'datetime',
        'placed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dineInSlot(): BelongsTo
    {
        return $this->belongsTo(DineInSlot::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
