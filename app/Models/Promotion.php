<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promotion extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'icon',
        'button_text',
        'button_url',
        'discount_amount',
        'discount_type',
        'minimum_order',
        'sort_order',
        'is_active',
        'starts_at',
        'ends_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'discount_amount' => 'decimal:2',
        'minimum_order' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Scope for active promotions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('starts_at')
                          ->orWhere('starts_at', '<=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('ends_at')
                          ->orWhere('ends_at', '>=', now());
                    });
    }

    /**
     * Scope for promotions by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Check if promotion is currently active
     */
    public function isCurrentlyActive()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        
        if ($this->starts_at && $this->starts_at > $now) {
            return false;
        }

        if ($this->ends_at && $this->ends_at < $now) {
            return false;
        }

        return true;
    }

    /**
     * Get formatted discount text
     */
    public function getFormattedDiscountAttribute()
    {
        if (!$this->discount_amount) {
            return null;
        }

        if ($this->discount_type === 'percentage') {
            return $this->discount_amount . '%';
        }

        return '$' . number_format($this->discount_amount, 2);
    }
}
