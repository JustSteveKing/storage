<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Disks;

use App\Models\Disk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class DeleteController
{
    public function __invoke(Request $request, Disk $disk): JsonResponse
    {
        if ( ! Gate::allows('delete', $disk)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $disk->delete();

        return new JsonResponse(
            data: [
                'message' => 'Disk has been deleted.',
            ],
            status: Response::HTTP_ACCEPTED,
        );
    }
}
