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
    public function definition(): array
    {
        return [
            'supabase_uid' => (string) Str::uuid(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'avatar_url' => null,
            'bio' => fake()->optional()->sentence(),
'role' => UserRole::MEMBER,
            'is_active' => true,
            'last_login_at' => null,
            'email_verified_at' => null,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => UserRole::ADMIN]);
    }

    public function editor(): static
    {
        return $this->state(fn () => ['role' => UserRole::WDITOR]);
    }
}