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

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_PREPARING = 'preparing';

    public const STATUS_READY = 'ready';

    public const STATUS_COMPLETED = 'completed';

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

    /**
     * @return array<string, string>
     */
    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING_PAYMENT => __('Pending Payment'),
            self::STATUS_PENDING => __('Pending Review'),
            self::STATUS_CONFIRMED => __('Confirmed'),
            self::STATUS_PREPARING => __('Preparing'),
            self::STATUS_READY => __('Ready'),
            self::STATUS_COMPLETED => __('Completed'),
            self::STATUS_CANCELLED => __('Cancelled'),
            self::STATUS_PAYMENT_FAILED => __('Payment Failed'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function manageableStatusLabels(): array
    {
        return [
            self::STATUS_PENDING => __('Pending Review'),
            self::STATUS_CONFIRMED => __('Confirmed'),
            self::STATUS_PREPARING => __('Preparing'),
            self::STATUS_READY => __('Ready for Pickup / Service'),
            self::STATUS_COMPLETED => __('Completed'),
            self::STATUS_CANCELLED => __('Cancelled'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function fulfillmentLabels(): array
    {
        return [
            self::FULFILLMENT_TAKEAWAY => __('Take Away'),
            self::FULFILLMENT_DELIVERY => __('Delivery'),
            self::FULFILLMENT_DINE_IN => __('Dine In'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function paymentMethodLabels(): array
    {
        return [
            self::PAYMENT_METHOD_STRIPE => __('Stripe'),
            self::PAYMENT_METHOD_PAYPAL => __('PayPal'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function paymentStatusLabels(): array
    {
        return [
            self::PAYMENT_STATUS_PENDING => __('Pending'),
            self::PAYMENT_STATUS_PAID => __('Paid'),
            self::PAYMENT_STATUS_FAILED => __('Failed'),
            self::PAYMENT_STATUS_CANCELLED => __('Cancelled'),
        ];
    }
}
