<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class JokiType extends Model {
    protected $guarded = [];
    public function game() { return $this->belongsTo(Game::class); }
    public function tiers() { return $this->hasMany(JokiTier::class); }
}