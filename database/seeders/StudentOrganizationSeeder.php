<?php

namespace Database\Seeders;

use App\Models\StudentOrganization;
use Illuminate\Database\Seeder;

class StudentOrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $roots = [
            ['name' => 'MPM', 'slug' => 'mpm', 'sort_order' => 10, 'is_group' => false],
            ['name' => 'BEM', 'slug' => 'bem', 'sort_order' => 20, 'is_group' => false],
            ['name' => 'HMJ', 'slug' => 'hmj', 'sort_order' => 30, 'is_group' => true],
            ['name' => 'UKM', 'slug' => 'ukm', 'sort_order' => 40, 'is_group' => true],
        ];

        foreach ($roots as $r) {
            StudentOrganization::updateOrCreate(
                ['slug' => $r['slug']],
                [
                    'parent_id' => null,
                    'name' => $r['name'],
                    'sort_order' => $r['sort_order'],
                    'is_active' => true,
                    'is_group' => $r['is_group'],
                ]
            );
        }
    }
}
