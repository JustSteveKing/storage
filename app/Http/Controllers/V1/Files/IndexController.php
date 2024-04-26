<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Files;

use App\Http\Resources\V1\FileResource;
use App\Models\Disk;
use App\Models\File;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class IndexController
{
    public function __invoke(Request $request, Disk $disk): JsonResponse
    {
        if ( ! Gate::allows('view', $disk)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $files = File::query()->where(
            column: 'disk_id',
            operator: '=',
            value: $disk->id
        )->whereHas(
            relation: 'disk',
            callback: fn (Builder $builder) => $builder->where(
                column: 'user_id',
                operator: '=',
                value: auth()->id(),
            ),
        );

        if ($request->has('include')) {
            $files->with(
                relations: (array) $request->get('include'),
            );
        }

        return new JsonResponse(
            data: FileResource::collection(
                resource: $files->paginate(),
            ),
        );
    }
}
