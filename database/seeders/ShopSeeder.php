<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        for ($shopIndex = 1; $shopIndex <= 10; $shopIndex++) {

            // 1️⃣ Create Shop Admin
            $shop = User::create([
                'first_name'        => 'User',
                'last_name'         => (string) $shopIndex,
                'email'             => 'shop' . $shopIndex . '@example.com',
                'password'          => bcrypt('sadia123'),
                'role'              => 'shop_admin',
                'shop_name'         => 'Shop ' . $shopIndex,
                'shop_url'          => 'https://shop-' . $shopIndex . '-url.test',
                'shop_number'       => '0300' . rand(1000000, 9999999),
                'approval_status'   => 'approved',
                'email_verified_at' => now(),
            ]);

            // 2️⃣ Create 10 categories per shop
            for ($catIndex = 1; $catIndex <= 10; $catIndex++) {
                $categorySlug = 'shop' . $shopIndex . '-cat' . $catIndex;

                $category = Category::create([
                    'user_id'   => $shop->id,
                    'name'      => 'Category ' . $catIndex . ' Shop ' . $shopIndex,
                    'slug'      => $categorySlug,
                    'is_active' => true,
                ]);

                // 3️⃣ Create 10 subcategories per category
                for ($subCatIndex = 1; $subCatIndex <= 10; $subCatIndex++) {
                    $subCatSlug = $categorySlug . '-sub' . $subCatIndex;

                    $subCategory = SubCategory::create([
                        'user_id'     => $shop->id,
                        'category_id' => $category->id,
                        'name'        => 'SubCategory ' . $subCatIndex . ' Cat ' . $catIndex,
                        'slug'        => $subCatSlug,
                        'is_active'   => true,
                    ]);

                    // 4️⃣ Create 10 products per subcategory
                    for ($prodIndex = 1; $prodIndex <= 10; $prodIndex++) {
                        Product::create([
                            'user_id'         => $shop->id,
                            'category_id'     => $category->id,
                            'sub_category_id' => $subCategory->id,
                            'name'            => 'Product ' . $prodIndex . ' SubCat ' . $subCatIndex,
                            'sku'             => 'S' . $shopIndex . 'C' . $catIndex . 'SC' . $subCatIndex . 'P' . $prodIndex,
                            'price'           => rand(100, 1000),
                            'cost'            => rand(50, 900),
                            'quantity'        => rand(1, 50),
                            'image'           => 'https://via.placeholder.com/500?text=Shop' . $shopIndex . 'Prod' . $prodIndex,
                            'is_active'       => true,
                        ]);
                    }
                }
            }
        }
    }
}
