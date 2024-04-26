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
        $disks = Disk::query()
            ->where('user_id', auth()->id());

        if ($request->has('include')) {
            $disks->with(
                relations: explode(',', $request->input('include')),
            );
        }

        return new JsonResponse(
            data: DiskResource::collection(
                resource: $disks->paginate(),
            ),
        );
    }
}
