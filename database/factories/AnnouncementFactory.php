<?php

namespace Database\Factories;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition(): array
    {
        return [
            'title'        => $this->faker->sentence(6),
            'content'      => $this->faker->paragraph(),
            'audience'     => $this->faker->randomElement(['all', 'students', 'staff']),
            'priority'     => $this->faker->randomElement(['normal', 'high', 'urgent']),
            'publish_date' => now()->subDays(rand(0, 5)),
            'expiry_date'  => null,
            'is_published' => true,
            'send_email'   => false,
            'send_sms'     => false,
        ];
    }

    public function unpublished(): static
    {
        return $this->state(['is_published' => false]);
    }

    public function expired(): static
    {
        return $this->state(['expiry_date' => now()->subDay()]);
    }

    public function forStudents(): static
    {
        return $this->state(['audience' => 'students']);
    }
}
