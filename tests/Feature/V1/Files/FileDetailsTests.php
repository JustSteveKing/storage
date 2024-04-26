<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Files\ShowController;
use App\Models\Disk;
use App\Models\File;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('an unauthenticated user will not be able to get details about a file', function (): void {
    $file = File::factory()->create();

    getJson(
        uri: action(ShowController::class, [$file->disk->id, $file->id]),
    )->assertStatus(
        status: Response::HTTP_UNAUTHORIZED,
    );
})->group('api', 'files');

test('an authenticated user without a verified email cannot get the details of a file', function (): void {
    $user = User::factory()->create(['email_verified_at' => null]);
    $disk = Disk::factory()->for($user)->create();
    $file = File::factory()->for($disk)->create();

    actingAs($user)->getJson(
        uri: action(ShowController::class, [$file->disk->id, $file->id]),
    )->assertStatus(
        status: Response::HTTP_CONFLICT,
    );
})->group('api', 'files');

test();
