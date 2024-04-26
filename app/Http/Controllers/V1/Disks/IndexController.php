<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Disks;

use App\Http\Resources\V1\DiskResource;
use App\Models\Disk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class IndexController
{
    public function __invoke(Request $request): JsonResponse
    {
        $disks = Disk::query()->where(
            column: 'user_id',
            operator: '=',
            value: auth()->id(),
        );

        if ($request->has('include')) {
            $disks->with(
                relations: explode(
                    separator: ',',
                    string: $request->string('include')->toString(),
                ),
            );
        }

        return new JsonResponse(
            data: DiskResource::collection(
                resource: $disks->paginate(),
            ),
        );
    }
}
