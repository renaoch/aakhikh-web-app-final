<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     * No password — authentication is handled by Supabase.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supabase_id'       => (string) Str::uuid(),
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'phone'             => fake()->optional()->phoneNumber(),
            'avatar'            => null,
            'role'              => UserRole::Member,
            'is_active'         => true,
            'last_login_at'     => null,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => UserRole::Admin]);
    }

    public function editor(): static
    {
        return $this->state(fn () => ['role' => UserRole::Editor]);
    }
}
