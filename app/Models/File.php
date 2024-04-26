<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $name
 * @property string $extension
 * @property string $mime
 * @property string $visibility
 * @property string $path
 * @property int $size
 * @property object $meta
 * @property string $disk_id
 * @property null|CarbonInterface $last_modified_at
 * @property null|CarbonInterface $created_at
 * @property null|CarbonInterface $updated_at
 * @property null|CarbonInterface $deleted_at
 * @property Disk $disk
 */
final class File extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    /** @var array<int,string> */
    protected $fillable = [
        'name',
        'extension',
        'mime',
        'visibility',
        'path',
        'size',
        'meta',
        'disk_id',
        'last_modified_at',
    ];

    /** @return BelongsTo */
    public function disk(): BelongsTo
    {
        return $this->belongsTo(
            related: Disk::class,
            foreignKey: 'disk_id',
        );
    }

    /** @return array<string,mixed> */
    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'meta' => AsArrayObject::class,
            'last_modified_at' => 'datetime',
        ];
    }
}
