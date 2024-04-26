<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Disks;

use App\Http\Requests\V1\Disks\StoreRequest;
use App\Http\Resources\V1\DiskResource;
use App\Models\Disk;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class StoreController
{
    public function __invoke(StoreRequest $request): JsonResponse
    {
        $disk = Disk::query()->create(array_merge(
            (array) $request->validated(),
            ['user_id' => auth()->id()],
        ));

        return new JsonResponse(
            data: new DiskResource(
                resource: $disk,
            ),
            status: Response::HTTP_CREATED,
        );
    }
}
