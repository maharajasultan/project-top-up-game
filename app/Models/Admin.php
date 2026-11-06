<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'role', 'is_active', 'remember_token'];
    protected $hidden   = ['password', 'remember_token'];
    protected $casts    = ['is_active' => 'boolean'];

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}