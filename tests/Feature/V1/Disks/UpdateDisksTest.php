<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Disks\UpdateController;
use App\Models\Disk;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\putJson;

use Symfony\Component\HttpFoundation\Response;

test('an unauthenticated user cannot update a disk', function (): void {
    $disk = Disk::factory()->create();

    putJson(
        uri: action(UpdateController::class, [$disk->id]),
        data: [],
    )->assertStatus(
        status: Response::HTTP_UNAUTHORIZED,
    );
})->group('api', 'disks');

test('an authenticated user cannot update a disk without the correct permissions', function (): void {
    $disk = Disk::factory()->create();

    actingAs(User::factory()->create(['email_verified_at' => null]))->putJson(
        uri: action(UpdateController::class, [$disk->id]),
        data: [],
    )->assertStatus(
        status: Response::HTTP_CONFLICT,
    );
});

test('an authenticated user must send the correct data to update a disk', function (): void {
    $disk = Disk::factory()->create();

    actingAs(User::factory()->create())->putJson(
        uri: action(UpdateController::class, [$disk->id]),
        data: []
    )->assertStatus(
        status: Response::HTTP_UNPROCESSABLE_ENTITY,
    )->assertJsonValidationErrors(
        'name'
    );
})->group('api', 'disks');

test('a user is unable to update a disk they do not own', function (): void {
    $disk = Disk::factory()->create();

    actingAs(User::factory()->create())->putJson(
        uri: action(UpdateController::class, [$disk->id]),
        data: [
            'name' => uniqid('', true),
        ],
    )->assertStatus(
        status: Response::HTTP_FORBIDDEN,
    );
})->group('api', 'disks');

test('a user is able to update their own disk', function (): void {
    $user = User::factory()->create();
    $disk = Disk::factory()->for($user)->create();

    actingAs($user)->putJson(
        uri: action(UpdateController::class, [$disk->id]),
        data: [
            'name' => $name = uniqid('', true),
        ],
    )->assertStatus(
        status: Response::HTTP_ACCEPTED,
    )->assertJson(
        fn (AssertableJson $json) => $json
            ->has('id')
            ->has('name')
            ->has('disk')
            ->where('name', $name)
            ->etc(),
    );

})->group('api', 'disks');
