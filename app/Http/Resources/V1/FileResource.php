<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Http\Resources\DateResource;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property File $resource
 */
final class FileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'extension' => $this->resource->extension,
            'mime' => $this->resource->mime,
            'size' => $this->resource->size,
            'visibility' => $this->resource->visibility,
            'path' => $this->resource->path,
            'meta' => $this->resource->meta,
            'last_modified' => new DateResource(
                resource: $this->resource->last_modified_at,
            ),
        ];
    }
}
