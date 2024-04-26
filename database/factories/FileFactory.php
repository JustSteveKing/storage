<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Disk;
use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

final class FileFactory extends Factory
{
    /** @var class-string<Model> */
    protected $model = File::class;

    /** @return array<string,mixed> */
    public function definition(): array
    {
        return [
            'name' => $this->faker->file(),
            'extension' => $this->faker->fileExtension(),
            'mime' => $this->faker->mimeType(),
            'visibility' => $this->faker->randomElement(['public', 'private']),
            'path' => $this->faker->filePath(),
            'size' => $this->faker->randomNumber(),
            'meta' => null,
            'disk_id' => Disk::factory(),
            'last_modified_at' => $this->faker->dateTimeBetween('-1 years', 'now'),
        ];
    }
}
