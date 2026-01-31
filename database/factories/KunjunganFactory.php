<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kunjungan>
 */
class KunjunganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_pengunjung' => $this->faker->name(),
            'nik_pengunjung' => $this->faker->numerify('################'),
            'no_wa_pengunjung' => $this->faker->phoneNumber(),
            'email_pengunjung' => $this->faker->unique()->safeEmail(),
            'alamat_pengunjung' => $this->faker->address(),
            'nama_wbp' => $this->faker->name(),
            'hubungan' => $this->faker->randomElement(['Orang Tua', 'Suami / Istri', 'Anak', 'Saudara', 'Teman']),
            'tanggal_kunjungan' => Carbon::now()->next(Carbon::TUESDAY)->format('Y-m-d'),
            'nomor_antrian_harian' => $this->faker->unique()->numberBetween(1, 200),
            'sesi' => null,
            'status' => 'pending',
        ];
    }
}
