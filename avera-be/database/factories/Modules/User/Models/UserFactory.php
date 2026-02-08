<?php

namespace Database\Factories\Modules\User\Models;

use App\Modules\Image\Models\Image;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'identity_core_id' => Str::uuid(),
            'username' => $this->faker->unique()->userName(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'role' => 'user',
            'status' => 'active',
            'gender' => $this->faker->randomElement(['male','female','other']),
            'phone_number' => $this->faker->phoneNumber(),
        ];
    }

    public function seller(): static
    {
        return $this->state(fn () => ['role' => 'seller']);
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {

            $avatarImage = Image::factory()->create([
                'owner_type' => 'user_avatar',
                'owner_id' => $user->id,
                'path' => 'samples/upscale-face-1.jpg'
            ]);

            $user->update([
                'avatar_image_id' => $avatarImage->id,
            ]);

        });
    }
}
