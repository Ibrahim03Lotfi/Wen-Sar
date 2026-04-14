<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GovernorateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $governorates = [
            'دمشق',
            'ريف دمشق',
            'حلب',
            'حمص',
            'حماة',
            'اللاذقية',
            'طرطوس',
            'السويداء',
            'درعا',
            'القنيطرة',
            'الرقة',
            'الحسكة',
            'دير الزور',
            'إدلب',
        ];

        foreach ($governorates as $name) {
            \App\Models\Governorate::firstOrCreate(['name' => $name]);
        }
    }
}
