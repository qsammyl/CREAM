<?php

namespace Database\Factories;

use App\Enums\EnquiryStatus;
use App\Enums\EnquiryType;
use App\Models\Enquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enquiry>
 */
class EnquiryFactory extends Factory
{
    protected $model = Enquiry::class;

    public function definition(): array
    {
        return [
            'type' => EnquiryType::Contact,
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'country' => fake()->country(),
            'message' => fake()->sentence(12),
            'subject' => 'New enquiry',
            'details' => ['budget' => '300K – 500K', 'timeline' => 'Exploring'],
            'status' => EnquiryStatus::New,
        ];
    }

    public function type(EnquiryType $type): static
    {
        return $this->state(['type' => $type, 'subject' => $type->subject()]);
    }

    public function read(): static
    {
        return $this->state(['status' => EnquiryStatus::Read]);
    }

    public function spam(): static
    {
        return $this->state(['status' => EnquiryStatus::Spam]);
    }
}
