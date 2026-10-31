<?php

namespace Database\Factories;

use App\Models\NewsletterSubscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NewsletterSubscriber>
 */
class NewsletterSubscriberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'status' => NewsletterSubscriber::STATUS_SUBSCRIBED,
            'consented_at' => now(),
            'subscribed_at' => now(),
            'unsubscribed_at' => null,
            'source' => 'footer',
        ];
    }
}
