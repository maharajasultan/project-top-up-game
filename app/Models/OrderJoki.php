<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderJoki extends Model
{
    protected $table = 'orders_joki';
    protected $guarded = [];
    protected $casts = [
        'paid_at'    => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function jokiType()
    {
        return $this->belongsTo(JokiType::class);
    }

    public function jokiTier()
    {
        return $this->belongsTo(JokiTier::class);
    }

    public function loginMethod()
    {
        return $this->belongsTo(JokiLoginMethod::class, 'login_method_id');
    }

    public function assignedAdmin()
    {
        return $this->belongsTo(Admin::class, 'assigned_admin_id');
    }

    public function notificationLogs()
    {
        return $this->hasMany(NotificationLog::class, 'order_id')->where('order_type', 'joki');
    }
}