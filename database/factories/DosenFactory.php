<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DosenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = User::class;
    public function definition(): array
    {
        return [
            'username' => fake()->userName() . fake()->unique()->randomNumber(5, true),
            'username' => fake()->name(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'tmp_lahir' => fake()->city(),
            'tgl_lahir' => fake()->date(),
            'gender' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('Dosen');
        });
    }
}
