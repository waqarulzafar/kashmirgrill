<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'date',
        'time',
        'persons',
        'table_preference',
        'selected_menu',
        'additional_notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
