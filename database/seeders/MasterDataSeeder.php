<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Game;
use App\Models\TopupProduct;
use App\Models\JokiType;
use App\Models\JokiTier;
use App\Models\JokiLoginMethod;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Game::truncate();
        TopupProduct::truncate();
        JokiType::truncate();
        JokiTier::truncate();
        JokiLoginMethod::truncate();
        Schema::enableForeignKeyConstraints();

        // 1. GAMES
        $ml = Game::create([
            'name' => 'Mobile Legends',
            'slug' => 'mobile-legends',
            'has_server_id' => true,
            'available_topup' => true,
            'available_joki' => true,
            'is_active' => true,
            'sort_order' => 1
        ]);

        $ff = Game::create([
            'name' => 'Free Fire',
            'slug' => 'free-fire',
            'has_server_id' => false,
            'available_topup' => true,
            'available_joki' => true,
            'is_active' => true,
            'sort_order' => 2
        ]);

        $pubg = Game::create([
            'name' => 'PUBG Mobile',
            'slug' => 'pubg-mobile',
            'has_server_id' => false,
            'available_topup' => true,
            'available_joki' => true,
            'is_active' => true,
            'sort_order' => 3
        ]);

        // 2. TOP UP PRODUCTS
        $mlProducts = [
            ['name' => '86 Diamond', 'price' => 20000, 'original_price' => 25000, 'discount_value' => 20, 'discount_type' => 'percent', 'is_discount_active' => true, 'is_active' => true, 'sort_order' => 1],
            ['name' => '172 Diamond', 'price' => 38000, 'original_price' => null, 'discount_value' => null, 'discount_type' => null, 'is_discount_active' => false, 'is_active' => true, 'sort_order' => 2],
            ['name' => '257 Diamond', 'price' => 56000, 'original_price' => 65000, 'discount_value' => 14, 'discount_type' => 'percent', 'is_discount_active' => true, 'is_active' => true, 'sort_order' => 3, 'is_popular' => true],
            ['name' => '344 Diamond', 'price' => 74000, 'original_price' => null, 'discount_value' => null, 'discount_type' => null, 'is_discount_active' => false, 'is_active' => true, 'sort_order' => 4],
            ['name' => 'Weekly Diamond Pass', 'price' => 22000, 'original_price' => 28000, 'discount_value' => 21, 'discount_type' => 'percent', 'is_discount_active' => true, 'is_active' => true, 'sort_order' => 5, 'type' => 'pass'],
        ];

        foreach ($mlProducts as $p) {
            TopupProduct::create(array_merge($p, ['game_id' => $ml->id]));
        }

        $ffProducts = [
            ['name' => '70 Diamond', 'price' => 15000, 'original_price' => null, 'discount_value' => null, 'discount_type' => null, 'is_discount_active' => false, 'is_active' => true, 'sort_order' => 1],
            ['name' => '140 Diamond', 'price' => 29000, 'original_price' => 35000, 'discount_value' => 17, 'discount_type' => 'percent', 'is_discount_active' => true, 'is_active' => true, 'sort_order' => 2, 'is_popular' => true],
            ['name' => 'Membership Bonanza', 'price' => 65000, 'original_price' => 79000, 'discount_value' => 18, 'discount_type' => 'percent', 'is_discount_active' => true, 'is_active' => true, 'sort_order' => 3, 'type' => 'membership'],
        ];

        foreach ($ffProducts as $p) {
            TopupProduct::create(array_merge($p, ['game_id' => $ff->id]));
        }

        $pubgProducts = [
            ['name' => '60 UC', 'price' => 15000, 'original_price' => null, 'discount_value' => null, 'discount_type' => null, 'is_discount_active' => false, 'is_active' => true, 'sort_order' => 1],
            ['name' => '325 UC', 'price' => 75000, 'original_price' => 89000, 'discount_value' => 16, 'discount_type' => 'percent', 'is_discount_active' => true, 'is_active' => true, 'sort_order' => 2],
            ['name' => '660 UC', 'price' => 149000, 'original_price' => null, 'discount_value' => null, 'discount_type' => null, 'is_discount_active' => false, 'is_active' => true, 'sort_order' => 3, 'is_popular' => true],
        ];

        foreach ($pubgProducts as $p) {
            TopupProduct::create(array_merge($p, ['game_id' => $pubg->id]));
        }

        // 3. JOKI TYPES & TIERS
        // ML
        $jokiMl1 = JokiType::create(['game_id' => $ml->id, 'name' => 'Winrate Classic', 'slug' => 'winrate-classic', 'is_active' => true, 'sort_order' => 1]);
        JokiTier::create(['joki_type_id' => $jokiMl1->id, 'tier_name' => 'Per Match', 'price_per_unit' => 3000, 'min_order' => 3]);

        $jokiMl2 = JokiType::create(['game_id' => $ml->id, 'name' => 'Rank', 'slug' => 'rank', 'is_active' => true, 'sort_order' => 2]);
        JokiTier::create(['joki_type_id' => $jokiMl2->id, 'tier_name' => 'Warrior - Elite', 'price_per_unit' => 2000, 'min_order' => 3]);
        JokiTier::create(['joki_type_id' => $jokiMl2->id, 'tier_name' => 'Elite - Master', 'price_per_unit' => 3000, 'min_order' => 3]);
        JokiTier::create(['joki_type_id' => $jokiMl2->id, 'tier_name' => 'Master - Grandmaster', 'price_per_unit' => 4000, 'min_order' => 3]);

        // FF
        $jokiFf = JokiType::create(['game_id' => $ff->id, 'name' => 'Pengaman Rank', 'slug' => 'pengaman-rank', 'is_active' => true, 'sort_order' => 1]);
        JokiTier::create(['joki_type_id' => $jokiFf->id, 'tier_name' => 'Bronze - Silver', 'price_per_unit' => 5000, 'min_order' => 3]);

        // JOKI LOGIN METHODS
        JokiLoginMethod::create(['game_id' => $ml->id, 'method_name' => 'Moonton', 'is_active' => true]);
        JokiLoginMethod::create(['game_id' => $ml->id, 'method_name' => 'Facebook', 'is_active' => true]);
        JokiLoginMethod::create(['game_id' => $ff->id, 'method_name' => 'Facebook', 'is_active' => true]);
        JokiLoginMethod::create(['game_id' => $ff->id, 'method_name' => 'Garena', 'is_active' => true]);
    }
}
