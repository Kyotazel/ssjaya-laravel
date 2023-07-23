<?php

namespace Database\Seeders;

use App\Models\Pharmacy;
use App\Models\PharmacyProduct;
use App\Models\Product;
use Illuminate\Database\Seeder;

class PharmacyProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pharamcies = Pharmacy::select(['id_apotek', 'produk'])->get();

        $pharmacy_data = collect($pharamcies)->map(function ($pharmacy) {
            $pharmacy['produk'] = explode(', ', strtolower($pharmacy['produk']));

            return $pharmacy;
        });

        foreach ($pharmacy_data as $pharmacy) {
            foreach ($pharmacy['produk'] as $product) {
                $database_product = Product::select(['id'])->whereRaw("LOWER(nama) = '$product'")->first();
                if ($database_product) {
                    PharmacyProduct::create([
                        'product_id' => $database_product->id,
                        'pharmacy_id' => $pharmacy['id_apotek']
                    ]);
                }
            }
        }
    }
}
