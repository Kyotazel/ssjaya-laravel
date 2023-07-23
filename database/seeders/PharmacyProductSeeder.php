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

        $pharmacyProducts = [];
        $counter = 0;
        $batchSize = 1000; // Ubah sesuai kebutuhan

        foreach ($pharmacy_data as $pharmacy) {
            foreach ($pharmacy['produk'] as $product) {
                $database_product = Product::select('id')->whereRaw("LOWER(nama) = ?", [$product])->first();
                if ($database_product) {
                    $pharmacyProducts[] = [
                        'product_id' => $database_product->id,
                        'pharmacy_id' => $pharmacy['id_apotek'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $counter++;

                    // Lakukan insert jika counter mencapai batch size
                    if ($counter === $batchSize) {
                        PharmacyProduct::insert($pharmacyProducts);
                        $pharmacyProducts = []; // Reset array untuk kelompok berikutnya
                        $counter = 0; // Reset counter
                    }
                }
            }
        }

        // Insert sisanya (jika ada)
        if (!empty($pharmacyProducts)) {
            PharmacyProduct::insert($pharmacyProducts);
        }
    }
}
