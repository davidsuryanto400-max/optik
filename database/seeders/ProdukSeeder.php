<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\Cabang;
use App\Models\Gudang;
use App\Models\Tipe;
use App\Models\Kategori;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear old data to prevent duplication during re-seeding
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Produk::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // 1. Ensure a Cabang exists
        $cabang = Cabang::firstOrCreate(
            ['nama' => 'Cabang Utama'],
            ['alamat' => 'Jl. Kebon Jeruk No. 1', 'is_active' => true]
        );

        // 2. Ensure Gudang exists
        $gudang = Gudang::firstOrCreate(
            ['nama' => 'Gudang Utama'],
            ['alamat' => 'Jl. Gudang Peluru', 'cabang_id' => $cabang->id, 'is_active' => true]
        );

        // 3. Create common Tipe and Kategori
        $typesAndCategories = [
            'Frame' => ['Metal', 'Plastic', 'Titanium', 'Acetate', 'Mixed Material'],
            'Lensa' => ['Single Vision', 'Bifocal', 'Progressive', 'Blue Light Control', 'Photochromic'],
            'Sunglass' => ['Polarized', 'UV Protection', 'Sport', 'Fashion'],
            'Softlens' => ['Daily', 'Monthly', 'Color', 'Toric'],
            'Care' => ['Solution', 'Lens Cloth', 'Cleaner Spray'],
        ];

        foreach ($typesAndCategories as $typeName => $categories) {
            $tipe = Tipe::firstOrCreate(['nama' => $typeName]);

            foreach ($categories as $catName) {
                Kategori::firstOrCreate(
                    ['nama' => $catName, 'tipe_id' => $tipe->id]
                );
            }
        }

        // 4. Generate Products for each Type
        $tipes = Tipe::with('kategoris')->get();
        $gudangIds = Gudang::pluck('id')->toArray();

        foreach ($tipes as $tipe) {
            // Determine how many products to generate per type
            $count = match ($tipe->nama) {
                'Frame' => 45,
                'Lensa' => 30,
                'Sunglass' => 15,
                'Softlens' => 5,
                'Care' => 5,
                default => 5,
            };

            for ($i = 0; $i < $count; $i++) {
                $kategori = $tipe->kategoris->random();
                $gudangId = $gudangIds[array_rand($gudangIds)];

                // Generate realistic data based on Type
                $brands = match ($tipe->nama) {
                    'Frame', 'Sunglass' => ['Ray-Ban', 'Oakley', 'Gucci', 'Police', 'Nike', 'Tom Ford'],
                    'Lensa' => ['Essilor', 'Hoya', 'Zeiss', 'Crizal', 'Rodenstock'],
                    'Softlens' => ['Acuvue', 'Air Optix', 'FreshLook', 'Biofinity'],
                    'Care' => ['ReNu', 'Opti-Free', 'Biotrue', 'AoSept'],
                    default => ['Generic'],
                };

                $colors = ['Black', 'Gold', 'Silver', 'Gunmetal', 'Tortoise', 'Blue', 'Red', 'Brown', 'Clear'];
                $brand = $brands[array_rand($brands)];
                $color = $colors[array_rand($colors)];

                $name = match ($tipe->nama) {
                    'Frame' => "$brand " . $kategori->nama . " Frame " . $color,
                    'Lensa' => "$brand " . $kategori->nama . " Lens",
                    'Sunglass' => "$brand " . $kategori->nama . " $color",
                    'Softlens' => "$brand " . $kategori->nama . " Contact Lens",
                    'Care' => "$brand " . $kategori->nama,
                    default => "$brand Product",
                };

                Produk::factory()->create([
                    'tipe_id' => $tipe->id,
                    'kategori_id' => $kategori->id,
                    'gudang_id' => $gudangId,
                    'nama' => $name,
                    'merek' => $brand,
                    'warna' => ($tipe->nama == 'Lensa' || $tipe->nama == 'Care') ? 'Clear' : $color,
                ]);
            }
        }
    }
}
