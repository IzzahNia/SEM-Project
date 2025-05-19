<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the product data
        $products = [
            [
                'product_name' => 'Benzon 22oz Cup 100pcs',
                'product_serial_number' => 'BZC022',
                'product_description' => 'Benzon 22oz size cup 100pcs.',
                'product_quantity' => 100,
                'product_category' => 'Cup',
                'product_purchase_price' => 10.00,
                'product_selling_price' => 15.00,
                'product_image' => '1735226262.jpg',
                'product_status' => 'active',
            ],
            [
                'product_name' => 'Benzon Lunch Box 100pcs',
                'product_serial_number' => 'BZL001',
                'product_description' => 'Eco-friendly Benzon Plastic Lunch Box 100pcs',
                'product_quantity' => 10,
                'product_category' => 'Lunch Box',
                'product_purchase_price' => 9.00,
                'product_selling_price' => 14.00,
                'product_image' => '1734326161.jpg',
                'product_status' => 'active',
            ],
            [
                'product_name' => 'Benzon Plastic 9" Plate',
                'product_serial_number' => 'BX-PL09',
                'product_description' => 'Eco-friendly Benzon Plastic plate 100pcs (9 inch).',
                'product_quantity' => 50,
                'product_category' => 'Plate',
                'product_purchase_price' => 8.00,
                'product_selling_price' => 12.00,
                'product_image' => '1734326235.jpg',
                'product_status' => 'active',
            ],
            [
                'product_name' => '6" Spoon 50pcs',
                'product_serial_number' => 'SPN6001',
                'product_description' => 'Plastic Spoon 50pcs (6 inch).',
                'product_quantity' => 60,
                'product_category' => 'Plate',
                'product_purchase_price' => 1.20,
                'product_selling_price' => 2.00,
                'product_image' => '1734326196.jpg',
                'product_status' => 'active',
            ],
            [
                'product_name' => '32" Bowl 50pcs',
                'product_serial_number' => 'BX-B-32',
                'product_description' => '32" Plastic Bowl 50pcs.',
                'product_quantity' => 50,
                'product_category' => 'Bowl',
                'product_purchase_price' => 4.00,
                'product_selling_price' => 7.00,
                'product_image' => 'benzon-bowl.jpg',
                'product_status' => 'active',
            ],
        ];

        // Seed the products
        foreach ($products as $productData) {
            Product::updateOrCreate(
                ['product_serial_number' => $productData['product_serial_number']], // Check by serial number
                $productData
            );
        }
    }
}
