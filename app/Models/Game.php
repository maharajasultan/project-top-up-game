<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Game extends Model {
    protected $guarded = [];

    public function topupProducts() { return $this->hasMany(TopupProduct::class); }
    public function jokiTypes() { return $this->hasMany(JokiType::class); }
    public function jokiLoginMethods() { return $this->hasMany(JokiLoginMethod::class); }

    /** URL banner game – gunakan file upload jika ada, fallback ke assets lama */
    public function getBannerUrlAttribute(): string
    {
        if ($this->banner) {
            return Storage::url($this->banner);
        }
        $map = [
            'mobile-legends' => '/images/games/ml_banner.png',
            'free-fire'      => '/images/games/ff_banner.png',
            'pubg-mobile'    => '/images/games/pubg_banner.png',
        ];
        return url($map[$this->slug] ?? '/images/games/default.png');
    }
}