<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenseCategories = [
            ['name' => 'Makanan & Minuman', 'color' => '#FF6B6B'],
            ['name' => 'Transportasi', 'color' => '#4ECDC4'],
            ['name' => 'Hiburan', 'color' => '#45B7D1'],
            ['name' => 'Belanja', 'color' => '#96CEB4'],
            ['name' => 'Kesehatan', 'color' => '#FFEAA7'],
            ['name' => 'Pendidikan', 'color' => '#DDA0DD'],
            ['name' => 'Tagihan', 'color' => '#98D8C8'],
            ['name' => 'Rumah Tangga', 'color' => '#F7DC6F'],
            ['name' => 'Komunikasi', 'color' => '#BB8FCE'],
            ['name' => 'Lain-lain', 'color' => '#AED6F1'],
        ];

        $incomeCategories = [
            ['name' => 'Gaji', 'color' => '#52C41A'],
            ['name' => 'Bonus', 'color' => '#13C2C2'],
            ['name' => 'Freelance', 'color' => '#1890FF'],
            ['name' => 'Investasi', 'color' => '#722ED1'],
            ['name' => 'Hadiah', 'color' => '#FA8C16'],
            ['name' => 'Lain-lain', 'color' => '#52C41A'],
        ];

        foreach ($expenseCategories as $category) {
            Category::create([
                'user_id' => null, // Global category
                'name' => $category['name'],
                'type' => 'expense',
                'color' => $category['color'],
            ]);
        }

        foreach ($incomeCategories as $category) {
            Category::create([
                'user_id' => null, // Global category
                'name' => $category['name'],
                'type' => 'income',
                'color' => $category['color'],
            ]);
        }
    }
}
