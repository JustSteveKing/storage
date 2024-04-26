<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Casts\AsEncryptedArrayObject;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $name
 * @property string $disk
 * @property object $properties
 * @property string $user_id
 * @property null|CarbonInterface $created_at
 * @property null|CarbonInterface $updated_at
 * @property User $user
 * @property Collection<File> $files
 */
final class Disk extends Model
{
    use HasFactory;
    use HasUuids;

    /** @var array<int,string> */
    protected $fillable = [
        'name',
        'disk',
        'properties',
        'user_id',
    ];

    /** @return BelongsTo */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id',
        );
    }

    /** @return HasMany */
    public function files(): HasMany
    {
        return $this->hasMany(
            related: File::class,
            foreignKey: 'disk_id',
        );
    }

    /** @return array<string,class-string> */
    protected function casts(): array
    {
        return [
            'properties' => AsEncryptedArrayObject::class,
        ];
    }
}
