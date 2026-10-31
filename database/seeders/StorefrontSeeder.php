<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class StorefrontSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Miels', 'slug' => 'miels', 'description' => 'Miels purs issus des ruchers marocains.'],
            ['name' => 'Amlou', 'slug' => 'amlou', 'description' => 'Amlou traditionnel préparé avec des ingrédients naturels.'],
            ['name' => 'Produits naturels', 'slug' => 'produits-naturels', 'description' => 'Huiles et soins naturels sélectionnés avec soin.'],
            ['name' => 'Coffrets cadeaux', 'slug' => 'coffrets-cadeaux', 'description' => 'Des assortiments naturels à offrir.'],
        ];
        $categoryIds = [];

        foreach ($categories as $categoryData) {
            $category = Category::query()->firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData,
            );
            $categoryIds[$category->slug] = $category->id;
        }

        $products = [
            ['category' => 'miels', 'name' => "Miel d'Eucalyptus", 'slug' => 'miel-eucalyptus', 'description' => 'Miel pur et naturel récolté au Maroc.', 'variants' => [['name' => '1 kg', 'weight_grams' => 1000, 'price' => 249], ['name' => '500 g', 'weight_grams' => 500, 'price' => 139]]],
            ['category' => 'amlou', 'name' => 'Amlou Traditionnel', 'slug' => 'amlou-traditionnel', 'description' => 'Amandes, huile d’argan et miel selon la recette traditionnelle.', 'variants' => [['name' => '250 g', 'weight_grams' => 250, 'price' => 135], ['name' => '500 g', 'weight_grams' => 500, 'price' => 245]]],
            ['category' => 'miels', 'name' => 'Miel de Thym', 'slug' => 'miel-thym', 'description' => 'Miel de thym à la saveur intense et aromatique.', 'variants' => [['name' => '1 kg', 'weight_grams' => 1000, 'price' => 279], ['name' => '500 g', 'weight_grams' => 500, 'price' => 155]]],
            ['category' => 'produits-naturels', 'name' => "Huile d'Argan", 'slug' => 'huile-argan', 'description' => 'Huile d’argan naturelle produite localement.', 'variants' => [['name' => '50 ml', 'weight_grams' => 50, 'price' => 199], ['name' => '100 ml', 'weight_grams' => 100, 'price' => 350]]],
            ['category' => 'miels', 'name' => 'Miel de Montagne', 'slug' => 'miel-montagne', 'description' => 'Miel sauvage aux arômes floraux des montagnes.', 'variants' => [['name' => '1 kg', 'weight_grams' => 1000, 'price' => 269], ['name' => '500 g', 'weight_grams' => 500, 'price' => 149]]],
        ];

        foreach ($products as $productData) {
            $variants = $productData['variants'];
            unset($productData['variants']);
            $categorySlug = $productData['category'];
            unset($productData['category']);

            $product = Product::query()->firstOrCreate(
                ['slug' => $productData['slug']],
                [...$productData, 'category_id' => $categoryIds[$categorySlug]],
            );

            foreach ($variants as $variantData) {
                ProductVariant::query()->firstOrCreate(
                    ['product_id' => $product->id, 'name' => $variantData['name']],
                    [...$variantData, 'stock_quantity' => 30, 'is_active' => true],
                );
            }
        }
    }
}
