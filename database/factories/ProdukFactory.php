<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Produk;
use App\Models\Tipe;
use App\Models\Kategori;
use App\Models\Gudang;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produk>
 */
class ProdukFactory extends Factory
{
    protected $model = Produk::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brands = ['Ray-Ban', 'Oakley', 'Gucci', 'Police', 'Nike', 'Essilor', 'Hoya', 'Zeiss', 'Crizal', 'Bausch + Lomb'];
        $frameTypes = ['Full Rim', 'Half Rim', 'Rimless', 'Aviator', 'Wayfarer', 'Cat Eye', 'Round', 'Square'];
        $lensTypes = ['Single Vision', 'Progressive', 'Bifocal', 'Blue Cut', 'Photochromic', 'Anti-Fatigue'];
        $colors = ['Black', 'Gold', 'Silver', 'Gunmetal', 'Tortoise', 'Blue', 'Red', 'Transparent', 'Brown'];

        $isFrame = $this->faker->boolean(60); // 60% chance it's a frame/sunglass, 40% lens/other

        if ($isFrame) {
            $brand = $this->faker->randomElement($brands);
            $type = $this->faker->randomElement($frameTypes);
            $color = $this->faker->randomElement($colors);
            $name = "$brand $type $color Frame";
            $merek = $brand;
            $warna = $color;
        } else {
            $brand = $this->faker->randomElement(['Essilor', 'Hoya', 'Zeiss', 'Crizal', 'Bausch + Lomb']);
            $type = $this->faker->randomElement($lensTypes);
            $name = "$brand $type Lens";
            $merek = $brand;
            $warna = 'Clear';
        }

        $hargaBeli = $this->faker->numberBetween(100000, 3000000); // 100k - 3jt
        // Randomize price to look more real (e.g. end in 000)
        $hargaBeli = round($hargaBeli / 5000) * 5000;

        $margin = $this->faker->numberBetween(20, 60);
        $hargaJual = $hargaBeli + ($hargaBeli * $margin / 100);
        $hargaJual = round($hargaJual / 5000) * 5000;

        return [
            'kode' => $this->faker->unique()->bothify('??-#####'), // e.g. RB-12345
            'nama' => $name,
            'merek' => $merek,
            'warna' => $warna,
            'harga_beli' => $hargaBeli,
            'harga_jual' => $hargaJual,
            'stok' => $this->faker->numberBetween(0, 50),
            'stok_minimum' => $this->faker->numberBetween(2, 5),
            // Foreign keys to be handled by seeder
            // 'tipe_id' => Tipe::factory(),
            // 'kategori_id' => Kategori::factory(),
            // 'gudang_id' => Gudang::factory(),
        ];
    }
}
