<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = require_once __DIR__ . '/data/categories.php';
        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category,
                ]);
        }

        $subCategoriesBracelet = require_once __DIR__ . '/data/subCategoryBracelet.php';
        foreach ($subCategoriesBracelet as $subCategoryBracelet) {
            DB::table('subcategories')->insert([
                'name' => $subCategoryBracelet,
                'category_id' => DB::table('categories')->where('name', 'bracelet')->pluck('id')[0],

            ]);
        }

        $subCategoriesEarrings = require_once __DIR__ . '/data/subCategoryEarrings.php';
        foreach ($subCategoriesEarrings as $subCategoryEarrings) {
            DB::table('subcategories')->insert([
                'name' => $subCategoryEarrings,
                'category_id' => DB::table('categories')->where('name', 'earrings')->pluck('id')[0],
            ]);
        }

        $subCategoriesNeck = require_once __DIR__ . '/data/subCategoryNeck.php';
        foreach ($subCategoriesNeck as $subCategoryNeck) {
            DB::table('subcategories')->insert([
                'name' => $subCategoryNeck,
                'category_id' => DB::table('categories')->where('name', 'neck')->pluck('id')[0],
            ]);
        }

        $subCategoriesRings = require_once __DIR__ . '/data/subCategoryRing.php';
        foreach ($subCategoriesRings as $subCategoryRings) {
            DB::table('subcategories')->insert([
                'name' => $subCategoryRings,
                'category_id' => DB::table('categories')->where('name', 'rings')->pluck('id')[0],
            ]);
        }
    }
}
