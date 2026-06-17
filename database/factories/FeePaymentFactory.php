<?php

namespace Database\Factories;

use App\Models\FeePayment;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeePaymentFactory extends Factory
{
    protected $model = FeePayment::class;

    public function definition(): array
    {
        $due = $this->faker->randomFloat(2, 5000, 30000);
        return [
            'student_id'     => null,
            'challan_number' => 'CH-' . strtoupper($this->faker->unique()->bothify('####-???')),
            'fee_type'       => 'tuition',
            'semester_number'=> $this->faker->numberBetween(1, 8),
            'amount_due'     => $due,
            'amount_paid'    => 0,
            'fine_amount'    => 0,
            'discount_amount'=> 0,
            'payment_status' => 'pending',
            'due_date'       => now()->addDays(30),
            'payment_date'   => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn(array $attrs) => [
            'amount_paid'    => $attrs['amount_due'],
            'payment_status' => 'paid',
            'payment_date'   => now()->subDays(rand(1, 10)),
        ]);
    }

    public function overdue(): static
    {
        return $this->state([
            'payment_status' => 'overdue',
            'due_date'       => now()->subDays(10),
        ]);
    }
}
