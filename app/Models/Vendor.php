<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'business_description',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'logo',
        'banner',
        'status', // pending, approved, rejected, suspended
        'commission_rate',
        'minimum_payout',
        'total_sales',
        'total_orders',
        'rating',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'commission_rate' => 'decimal:2',
        'minimum_payout' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'total_orders' => 'integer',
        'rating' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    public function isActive(): bool
    {
        return $this->status === 'approved';
    }
}
