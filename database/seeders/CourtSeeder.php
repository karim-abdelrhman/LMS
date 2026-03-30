<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Court;
use App\Models\CourtCategory;

class CourtSeeder extends Seeder
{
    public function run(): void
    {
        $courts = [
            // Primary Courts
            ['name' => 'محكمة شمال القاهرة الابتدائية', 'category' => 'محكمة ابتدائية'],
            ['name' => 'محكمة جنوب القاهرة الابتدائية', 'category' => 'محكمة ابتدائية'],
            ['name' => 'محكمة الجيزة الابتدائية', 'category' => 'محكمة ابتدائية'],
            // Appeal Courts
            ['name' => 'محكمة استئناف القاهرة', 'category' => 'محكمة استئناف'],
            ['name' => 'محكمة استئناف الإسكندرية', 'category' => 'محكمة استئناف'],
            // Cassation
            ['name' => 'محكمة النقض', 'category' => 'محكمة النقض'],
            // Family
            ['name' => 'محكمة الأسرة مدينة نصر', 'category' => 'محكمة الأسرة'],
            // Administrative
            ['name' => 'محكمة القضاء الإداري', 'category' => 'محكمة القضاء الإداري'],
            // Others
            ['name' => 'محكمة الأمور المستعجلة القاهرة', 'category' => 'محكمة الأمور المستعجلة'],
            ['name' => 'محكمة جنايات القاهرة', 'category' => 'محكمة الجنايات'],
            ['name' => 'محكمة جنح قصر النيل', 'category' => 'محكمة الجنح'],
            ['name' => 'محكمة مرور القاهرة', 'category' => 'محكمة المرور'],
            ['name' => 'محكمة أمن الدولة العليا طوارئ', 'category' => 'محكمة أمن الدولة'],
            ['name' => 'محكمة القضاء العسكري', 'category' => 'محكمة القضاء العسكري'],
        ];

        foreach ($courts as $court) {
            $category = CourtCategory::where('name', $court['category'])->first();
            if ($category) {
                Court::firstOrCreate([
                    'name' => $court['name'],
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}
