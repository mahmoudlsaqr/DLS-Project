<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('regions')->insert([
            ['code' => 'RD', 'name_ar' => 'منطقة الرياض', 'name_en' => 'Riyadh', 'population' => 6777146, 'id' => 1],
            ['code' => 'MQ', 'name_ar' => 'منطقة مكة المكرمة', 'name_en' => 'Makkah', 'population' => 6915006, 'id' => 2],
            ['code' => 'MN', 'name_ar' => 'منطقة المدينة المنورة', 'name_en' => 'Madinah', 'population' => 1777933, 'id' => 3],
            ['code' => 'QA', 'name_ar' => 'منطقة القصيم', 'name_en' => 'Qassim', 'population' => 1215858, 'id' => 4],
            ['code' => 'SQ', 'name_ar' => 'المنطقة الشرقية', 'name_en' => 'Eastern Province', 'population' => 4105780, 'id' => 5],
            ['code' => 'AS', 'name_ar' => 'منطقة عسير', 'name_en' => 'Asir', 'population' => 1913392, 'id' => 6],
            ['code' => 'TB', 'name_ar' => 'منطقة تبوك', 'name_en' => 'Tabuk', 'population' => 791535, 'id' => 7],
            ['code' => 'HA', 'name_ar' => 'منطقة حائل', 'name_en' => 'Hail', 'population' => 597144, 'id' => 8],
            ['code' => 'SH', 'name_ar' => 'منطقة الحدود الشمالية', 'name_en' => 'Northern Borders', 'population' => 320524, 'id' => 9],
            ['code' => 'GA', 'name_ar' => 'منطقة جازان', 'name_en' => 'Jazan', 'population' => 1365110, 'id' => 10],
            ['code' => 'NG', 'name_ar' => 'منطقة نجران', 'name_en' => 'Najran', 'population' => 505652, 'id' => 11],
            ['code' => 'BA', 'name_ar' => 'منطقة الباحة', 'name_en' => 'Bahah', 'population' => 411888, 'id' => 12],
            ['code' => 'GO', 'name_ar' => 'منطقة الجوف', 'name_en' => 'Jawf', 'population' => 440009, 'id' => 13],
        ]);
    }
}
