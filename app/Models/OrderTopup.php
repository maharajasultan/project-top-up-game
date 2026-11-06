<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderTopup extends Model
{
    protected $table = 'orders_topup';
    protected $guarded = [];
    protected $casts = [
        'paid_at'    => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function product()
    {
        return $this->belongsTo(TopupProduct::class, 'product_id');
    }

    public function notificationLogs()
    {
        return $this->hasMany(NotificationLog::class, 'order_id')->where('order_type', 'topup');
    }
}