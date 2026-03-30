<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourtCategory;

class CourtCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'محكمة ابتدائية', // Primary Court
            'محكمة استئناف', // Court of Appeal
            'محكمة النقض', // Court of Cassation
            'محكمة الأسرة', // Family Court
            'محكمة القضاء الإداري', // Administrative Judiciary
            'محكمة الأمور المستعجلة', // Summary Proceedings Court
            'محكمة الأحداث', // Juvenile Court
            'محكمة الجنايات', // Criminal Court
            'محكمة الجنح', // Misdemeanor Court
            'محكمة المرور', // Traffic Court
            'محكمة أمن الدولة', // State Security Court
            'محكمة القضاء العسكري', // Military Court
        ];

        foreach ($categories as $category) {
            CourtCategory::firstOrCreate(['name' => $category]);
        }
    }
}
