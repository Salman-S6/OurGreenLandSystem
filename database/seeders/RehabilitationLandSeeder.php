<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\FarmLand\Models\Land;
use Modules\FarmLand\Models\Rehabilitation;

class RehabilitationLandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        $lands = Land::inRandomOrder()->take(10)->get();
        $rehabilitations = Rehabilitation::inRandomOrder()->take(5)->get();
        $user = User::inRandomOrder()->first(); 

        foreach ($lands as $land) {
            DB::table('rehabilitation_land')->insert([
                'land_id' => $land->id,
                'rehabilitation_id' => $rehabilitations->random()->id,
                'performed_by' => $user?->id ?? 1,
                'performed_at' => Carbon::now()->subDays(rand(1, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
