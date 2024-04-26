<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Disks;

use App\Http\Requests\V1\Disks\UpdateRequest;
use App\Http\Resources\V1\DiskResource;
use App\Models\Disk;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class UpdateController
{
    public function __invoke(UpdateRequest $request, Disk $disk): JsonResponse
    {
        if ( ! Gate::allows('update', $disk)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $disk->update((array) $request->validated());

        return new JsonResponse(
            data: new DiskResource(
                resource: $disk->refresh(),
            ),
            status: Response::HTTP_ACCEPTED,
        );
    }
}
