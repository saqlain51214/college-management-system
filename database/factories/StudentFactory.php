<?php

namespace Database\Factories;

use App\Enums\StudentStatusEnum;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        $seq = $this->faker->unique()->numberBetween(1, 9999);
        return [
            'roll_number'   => 'CS-' . date('Y') . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT),
            'name'          => $this->faker->name(),
            'father_name'   => $this->faker->name('male'),
            'email'         => $this->faker->unique()->safeEmail(),
            'phone'         => '03' . $this->faker->numerify('#########'),
            'status'        => StudentStatusEnum::Active->value,
            'is_active'     => true,
            'batch_year'    => date('Y'),
            'current_semester' => 1,
            'portal_password'  => null,
        ];
    }

    public function withPassword(string $password = 'student@1234'): static
    {
        return $this->state(['portal_password' => Hash::make($password)]);
    }

    public function inactive(): static
    {
        return $this->state([
            'status'    => StudentStatusEnum::Inactive->value,
            'is_active' => false,
        ]);
    }
}
