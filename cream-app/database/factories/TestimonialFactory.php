<?php

namespace Database\Factories;

use App\Enums\TestimonialStatus;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    protected $model = Testimonial::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'role' => fake()->randomElement(['Diaspora Buyer · London', 'Buyer · East Legon', 'Landlord · Accra', 'Investor · New York']),
            'rating' => 5,
            'body' => fake()->paragraph(),
            'consent' => true,
            'status' => TestimonialStatus::Pending,
            'source' => 'web',
        ];
    }

    public function approved(): static
    {
        return $this->state(['status' => TestimonialStatus::Approved]);
    }

    public function archived(): static
    {
        return $this->state(['status' => TestimonialStatus::Archived]);
    }

    public function spam(): static
    {
        return $this->state(['status' => TestimonialStatus::Spam]);
    }

    public function featured(): static
    {
        return $this->state(['is_featured' => true]);
    }

    /** Feedback left without consent to publish — valuable, but never publishable. */
    public function noConsent(): static
    {
        return $this->state(['consent' => false]);
    }
}
