<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LegalCase;
use App\Models\CaseCategory;
use App\Models\Court;

class LegalCaseSeeder extends Seeder
{
    public function run(): void
    {
        $cases = [
            [
                'title' => 'مطالبة بحقوق موظف',
                'case_number' => 1001,
                'case_type' => 1,
                'category' => 'مدني',
                'court' => 'محكمة شمال القاهرة الابتدائية',
            ],
            [
                'title' => 'دعوى تعويض ضد الشركة',
                'case_number' => 1002,
                'case_type' => 2,
                'category' => 'تجاري',
                'court' => 'محكمة جنوب القاهرة الابتدائية',
            ],
            [
                'title' => 'دعوى عمالية',
                'case_number' => 1003,
                'case_type' => 3,
                'category' => 'عمالي',
                'court' => 'محكمة الجيزة الابتدائية',
            ],
        ];

        foreach ($cases as $case) {
            $category = CaseCategory::where('name', $case['category'])->first();
            $court = Court::where('name', $case['court'])->first();
            if ($category && $court) {
                LegalCase::firstOrCreate([
                    'title' => $case['title'],
                    'case_number' => $case['case_number'],
                    'case_type' => $case['case_type'],
                    'category_id' => $category->id,
                    'court_id' => $court->id,
                ]);
            }
        }
    }
}
