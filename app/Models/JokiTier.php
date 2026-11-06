<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JokiTier extends Model
{
    protected $guarded = [];
    protected $casts = [
        'price'               => 'float',
        'discount_value'      => 'float',
        'is_flat_price'       => 'boolean',
        'is_discount_active'  => 'boolean',
        'is_active'           => 'boolean',
        'discount_start_date' => 'date',
        'discount_end_date'   => 'date',
    ];

    public function jokiType()
    {
        return $this->belongsTo(JokiType::class);
    }

    /**
     * Harga final setelah diskon.
     */
    public function getFinalPriceAttribute(): float
    {
        if (!$this->is_discount_active) return $this->price;

        $now = now()->toDateString();
        if ($this->discount_start_date && $this->discount_start_date > $now) return $this->price;
        if ($this->discount_end_date   && $this->discount_end_date   < $now) return $this->price;

        if ($this->discount_type === 'percent') {
            return max(0, $this->price - ($this->price * $this->discount_value / 100));
        }

        return max(0, $this->price - $this->discount_value);
    }

    public function getIsOnDiscountAttribute(): bool
    {
        if (!$this->is_discount_active || !$this->discount_value) return false;
        $now = now()->toDateString();
        if ($this->discount_start_date && $this->discount_start_date > $now) return false;
        if ($this->discount_end_date   && $this->discount_end_date   < $now) return false;
        return true;
    }
}