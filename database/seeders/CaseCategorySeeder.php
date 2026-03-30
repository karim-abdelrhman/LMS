<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CaseCategory;

class CaseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'جنائي', // Criminal
            'مدني', // Civil
            'أحوال شخصية', // Personal Status
            'تجاري', // Commercial
            'إداري', // Administrative
            'عقاري', // Real Estate
            'عمالي', // Labor
            'استئناف', // Appeal
            'دستوري', // Constitutional
            'اقتصادي', // Economic
            'أحداث', // Juvenile
            'مرور', // Traffic
            'أمن دولة', // State Security
            'محكمة الأسرة', // Family Court
            'محكمة النقض', // Cassation Court
            'محكمة القضاء الإداري', // Administrative Judiciary
        ];

        foreach ($categories as $category) {
            CaseCategory::firstOrCreate(['name' => $category]);
        }
    }
}
