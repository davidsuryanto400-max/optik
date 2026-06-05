<?php
use App\Models\Pasien;
use Faker\Factory as Faker;
use Carbon\Carbon;

$faker = Faker::create('id_ID');

$count = 0;
for ($i = 0; $i < 50; $i++) {
    // Generate a birthday that is coming up within the next 30 days
    // The year should be between 1960 and 2010 to make them adults
    $year = $faker->numberBetween(1960, 2010);
    
    // Pick a date in the current year, between today and 30 days from now
    $upcomingDate = Carbon::now()->addDays($faker->numberBetween(0, 30));
    
    // Construct the birth date
    $tgl_lahir = Carbon::createFromDate($year, $upcomingDate->month, $upcomingDate->day)->format('Y-m-d');
    
    Pasien::create([
        'nama' => $faker->name,
        'alamat' => $faker->address,
        'no_hp' => $faker->phoneNumber,
        'tgl_lahir' => $tgl_lahir,
        'last_exam_date' => Carbon::now()->subDays($faker->numberBetween(10, 300))->format('Y-m-d'),
        'sph_r' => $faker->randomElement(['-0.50', '-1.00', '-1.50', '0.00', '+0.50']),
        'cyl_r' => $faker->randomElement(['-0.50', '0.00']),
        'sph_l' => $faker->randomElement(['-0.50', '-1.00', '-1.50', '0.00', '+0.50']),
        'cyl_l' => $faker->randomElement(['-0.50', '0.00']),
        'pd' => $faker->numberBetween(58, 68),
    ]);
    $count++;
}
echo "$count patients with upcoming birthdays generated successfully!";
