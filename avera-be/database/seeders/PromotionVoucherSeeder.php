<?php

namespace Database\Seeders;

use App\Models\Promotion;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promo = Promotion::create([
            'name' => 'New Year Sale',
            'code' => 'NY2026',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'start_at' => now()->subDays(1),
            'end_at' => now()->addDays(10),
        ]);

        $user = User::where('role', 'user')->first();

        Voucher::create([
            'code' => 'NYUSER10',
            'promotion_id' => $promo->id,
            'user_id' => $user->id,
            'status' => 'unused',
        ]);
    }
}
