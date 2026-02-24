<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_category_id',
        'name',
        'description',
        'price',
        'tags',
        'image_path',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    public function imageUrl(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        if (Str::startsWith($this->image_path, ['http://', 'https://'])) {
            return $this->image_path;
        }

        if (Str::startsWith($this->image_path, '/')) {
            return asset(ltrim($this->image_path, '/'));
        }

        if (Str::startsWith($this->image_path, 'assets/')) {
            return asset($this->image_path);
        }

        return asset('storage/' . ltrim($this->image_path, '/'));
    }

    public function tagList(): array
    {
        if (!$this->tags) {
            return [];
        }

        return collect(explode(',', $this->tags))
            ->map(fn (string $tag) => trim($tag))
            ->filter()
            ->values()
            ->all();
    }
}
