<?php

namespace Database\Factories;

use App\Models\AcademicProgram;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AcademicProgramFactory extends Factory
{
    protected $model = AcademicProgram::class;

    public function definition(): array
    {
        $name = $this->faker->randomElement(['BS Computer Science', 'BS Mathematics', 'BS Physics', 'BA English', 'BA Urdu']) . ' ' . $this->faker->unique()->numberBetween(1, 999);
        return [
            'name'            => $name,
            'short_name'      => strtoupper(Str::limit(Str::slug($name, ''), 6, '')),
            'slug'            => Str::slug($name),
            'code'            => strtoupper($this->faker->unique()->bothify('??###')),
            'degree_type'     => 'bs',
            'duration_years'  => 4,
            'total_semesters' => 8,
            'is_active'       => true,
            'show_on_website' => true,
            'sort_order'      => $this->faker->numberBetween(1, 100),
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
