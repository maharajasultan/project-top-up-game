<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Payment extends Model {
    protected $guarded = [];
    protected $casts = ['transaction_time' => 'datetime', 'raw_response' => 'array'];
}