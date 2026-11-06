<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TopupProduct extends Model
{
    protected $guarded = [];
    protected $casts = [
        'price'               => 'float',
        'modal_price'         => 'float',
        'original_price'      => 'float',
        'discount_value'      => 'float',
        'discount_start'      => 'date',
        'discount_end'        => 'date',
        'is_discount_active'  => 'boolean',
        'is_popular'          => 'boolean',
        'is_active'           => 'boolean',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Harga final setelah diskon (jika aktif & dalam periode).
     */
    public function getFinalPriceAttribute(): float
    {
        if (!$this->is_discount_active) return $this->price;

        $now = now()->toDateString();
        if ($this->discount_start && $this->discount_start > $now) return $this->price;
        if ($this->discount_end   && $this->discount_end   < $now) return $this->price;

        if ($this->discount_type === 'percent') {
            return max(0, $this->price - ($this->price * $this->discount_value / 100));
        }

        return max(0, $this->price - $this->discount_value);
    }

    /**
     * True jika produk sedang diskon dan dalam periode aktif.
     */
    public function getIsOnDiscountAttribute(): bool
    {
        if (!$this->is_discount_active || !$this->discount_value) return false;
        $now = now()->toDateString();
        if ($this->discount_start && $this->discount_start > $now) return false;
        if ($this->discount_end   && $this->discount_end   < $now) return false;
        return true;
    }
}