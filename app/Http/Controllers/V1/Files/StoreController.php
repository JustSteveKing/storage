<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Files;

use App\Http\Requests\V1\Files\StoreRequest;
use App\Http\Resources\V1\FileResource;
use App\Models\Disk;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

final class StoreController
{
    public function __invoke(StoreRequest $request, Disk $disk): JsonResponse
    {
        if ( ! Gate::allows('upload', $disk)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $uploaded = $request->getUploadedFile();

        $upload = Storage::disk($disk->disk)->putFileAs(
            path: $request->string('path')->toString(),
            file: $uploaded,
            name: $uploaded->getClientOriginalName(),
        );

        if ( ! $upload) {
            throw new RuntimeException(
                message: 'Something went wrong.',
            );
        }

        $file = File::query()->create([
            'name' => $uploaded->getClientOriginalName(),
            'extension' => $uploaded->getClientOriginalExtension(),
            'mime' => $uploaded->getClientMimeType(),
            'visibility' => 'public',
            'path' => $uploaded->getPathname(),
            'size' => $uploaded->getSize(),
            'meta' => [],
            'disk_id' => $disk->id,
            'last_modified_at' => now(),
        ]);

        return new JsonResponse(
            data: new FileResource(
                resource: $file,
            ),
            status: Response::HTTP_CREATED,
        );
    }
}
