<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\Disk;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Disk $resource
 */
final class DiskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'disk' => $this->resource->disk,
            'properties' => $this->resource->properties,
            'user' => new UserResource(
                resource: $this->whenLoaded(
                    relationship: 'user',
                ),
            ),
            'files' => FileResource::collection(
                resource: $this->whenLoaded(
                    relationship: 'files',
                ),
            ),
        ];
    }
}
