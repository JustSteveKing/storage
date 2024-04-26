<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Disk;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

final class DiskFactory extends Factory
{
    /** @var class-string<Model> */
    protected $model = Disk::class;

    /** @return array<string,mixed> */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'disk' => 'local',
            'properties' => [
                'driver' => 'local',
                'root' => storage_path('app'),
                'throw' => false,
            ],
            'user_id' => User::factory(),
        ];
    }
}
