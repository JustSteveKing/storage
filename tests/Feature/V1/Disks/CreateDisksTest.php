<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Disks\StoreController;
use App\Models\Disk;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

use Symfony\Component\HttpFoundation\Response;

test('an unauthenticated user can not create a disk', function (): void {
    postJson(
        uri: action(StoreController::class),
        data: [],
    )->assertStatus(
        status: Response::HTTP_UNAUTHORIZED,
    );
})->group('api', 'disks');

test('an authenticated user will get validation errors', function (): void {
    actingAs(User::factory()->create())->postJson(
        uri: action(StoreController::class),
        data: [],
    )->assertStatus(
        status: Response::HTTP_UNPROCESSABLE_ENTITY,
    )->assertJsonValidationErrors([
        'name',
        'disk',
    ]);
})->group('api', 'disks');

test('an authenticated user can store a disk', function (): void {
    actingAs(User::factory()->create())->postJson(
        uri: action(StoreController::class),
        data: [
            'name' => 'Test Disk',
            'disk' => 'local',
            'properties' => [
                'driver' => 'local',
                'root' => storage_path('app'),
                'throw' => false,
            ],
        ],
    )->assertStatus(
        status: Response::HTTP_CREATED,
    );
})->group('api', 'disks');

test('creating a disk gives the correct response payload', function (): void {
    actingAs(User::factory()->create())->postJson(
        uri: action(StoreController::class),
        data: [
            'name' => 'Test Disk',
            'disk' => 'local',
            'properties' => [
                'driver' => 'local',
                'root' => storage_path('app'),
                'throw' => false,
            ],
        ],
    )->assertJson(
        fn (AssertableJson $json) => $json
            ->has('id')
            ->has('name')
            ->has('disk')
            ->has('properties')
            ->etc()
    );

    expect(
        Disk::query()->count(),
    )->toEqual(1);
})->group('api', 'disks');
