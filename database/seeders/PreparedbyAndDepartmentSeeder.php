<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreparedbyAndDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Table 1
        DB::table('departments')->insert([
            [
                'code' => 'wh',
                'name' => 'Warehouse',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'mrktng',
                'name' => 'Marketing',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'acctng',
                'name' => 'Accounting',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'visual_mrch',
                'name' => 'Visual Merch',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'str_devt',
                'name' => 'Store Development',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'mngmnt',
                'name' => 'Management',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'humn_rsrcs',
                'name' => 'Human Resources',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'ecom',
                'name' => 'E-Commerce',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'brnd_tm',
                'name' => 'Brand Team',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'lrng_devt',
                'name' => 'Learning Development',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'it',
                'name' => 'Information Technology',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'rgltry',
                'name' => 'Regulatory',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'logstcs',
                'name' => 'Logistics',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'aaa',
                'name' => 'Triple A',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'sales_tm',
                'name' => 'Sales Team',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'ttk',
                'name' => 'Tiktok',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'secrty',
                'name' => 'Security Team',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);

        // Table 2
        DB::table('prepared_by_options')->insert([
            [
                'code' => 'sir_ben',
                'name' => 'Ben Amora',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'sir_sam',
                'name' => 'Sam Garcia',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'hans',
                'name' => 'Hans Joshua Jimenez',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'cohen',
                'name' => 'Cohen Venise Flores',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'arman',
                'name' => 'Armando Naesa',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
