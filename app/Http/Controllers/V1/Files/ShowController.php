<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Files;

use App\Http\Resources\V1\FileResource;
use App\Models\Disk;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class ShowController
{
    public function __invoke(Request $request, Disk $disk, File $file): JsonResponse
    {
        if ( ! Gate::allows('listContents', $disk)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        if ($file->disk->id !== $disk->id) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return new JsonResponse(
            data: new FileResource(
                resource: $file,
            ),
        );
    }
}
