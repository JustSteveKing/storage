<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Disks\DeleteController;
use App\Models\Disk;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\deleteJson;

test('an unauthenticated user cannot delete a disk', function (): void {
    $disk = Disk::factory()->create();

    deleteJson(
        uri: action(DeleteController::class, [$disk->id]),
    )->assertStatus(
        status: Response::HTTP_UNAUTHORIZED,
    );
})->group('api', 'disks');

test('an authenticated user cannot delete a disk they do not own', function (): void {
    $disk = Disk::factory()->create();

    actingAs(User::factory()->create())->deleteJson(
        uri: action(DeleteController::class, [$disk->id]),
    )->assertStatus(
        status: Response::HTTP_FORBIDDEN,
    );
})->group('api', 'disks');

test('an authenticated user can delete a disk they own', function (): void {
    $user = User::factory()->create();
    $disk = Disk::factory()->for($user)->create();

    actingAs($user)->deleteJson(
        uri: action(DeleteController::class, [$disk->id]),
    )->assertStatus(
        status: Response::HTTP_ACCEPTED,
    )->assertJson(fn (AssertableJson $json) => $json
        ->where('message', 'Disk has been deleted.')
        ->etc(),
    );
})->group('api', 'disks');
