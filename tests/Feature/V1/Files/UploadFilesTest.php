<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Files\StoreController;
use App\Models\Disk;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

use Symfony\Component\HttpFoundation\Response;

test('an unauthenticated user cannot upload a file to a disk', function (): void {
    Storage::fake();
    $disk = Disk::factory()->create();

    postJson(
        uri: action(StoreController::class, [$disk->id]),
        data: [
            'file' => UploadedFile::fake()->create(
                name: 'test',
            ),
            'path' => '/path/to/file',
            'name' => Str::uuid()->toString(),
        ]
    )->assertStatus(
        status: Response::HTTP_UNAUTHORIZED,
    );
})->group('api', 'files');

test('an authenticated user without a verified email cannot upload a file', function (): void {
    Storage::fake();
    $disk = Disk::factory()->create();

    actingAs(User::factory()->create(['email_verified_at' => null]))->postJson(
        uri: action(StoreController::class, [$disk->id]),
        data: [
            'file' => UploadedFile::fake()->create(
                name: 'test',
            ),
            'path' => '/path/to/file',
            'name' => Str::uuid()->toString(),
        ]
    )->assertStatus(
        status: Response::HTTP_CONFLICT,
    );
})->group('api', 'files');

test('an authenticated user cannot upload a file to a disk they do not own', function (): void {
    Storage::fake();
    $user = User::factory()->create();
    $disk = Disk::factory()->create();

    actingAs($user)->postJson(
        uri: action(StoreController::class, [$disk->id]),
        data: [
            'file' => UploadedFile::fake()->create(
                name: 'test',
            ),
            'path' => '/path/to/file',
            'name' => Str::uuid()->toString(),
        ]
    )->assertStatus(
        status: Response::HTTP_FORBIDDEN,
    );
})->group('api', 'files');

test('an authenticated user can upload a file to a disk they own', function (): void {
    Storage::fake();
    $user = User::factory()->create();
    $disk = Disk::factory()->for($user)->create();

    actingAs($user)->postJson(
        uri: action(StoreController::class, [$disk->id]),
        data: [
            'file' => UploadedFile::fake()->create(
                name: 'test',
            ),
            'path' => '/path/to/file',
            'name' => Str::uuid()->toString(),
        ]
    )->assertStatus(
        status: Response::HTTP_CREATED,
    );
})->group('api', 'files');

test('an authenticated user will get the correct response payload', function (): void {
    Storage::fake();
    $user = User::factory()->create();
    $disk = Disk::factory()->for($user)->create();

    actingAs($user)->postJson(
        uri: action(StoreController::class, [$disk->id]),
        data: [
            'file' => UploadedFile::fake()->create(
                name: 'test',
            ),
            'path' => '/path/to/file',
            'name' => Str::uuid()->toString(),
        ]
    )->assertJson(
        fn (AssertableJson $json) => $json
            ->has('id')
            ->has('name')
            ->has('extension')
            ->has('mime')
            ->has('size')
            ->has('visibility')
            ->has('path')
            ->has('meta')
            ->has('last_modified.human')
            ->has('last_modified.string')
            ->has('last_modified.local')
            ->has('last_modified.timestamp')
            ->etc()
    );
})->group('api', 'files');

test('an authenticated user will have the request validated correctly', function (): void {
    Storage::fake();
    $user = User::factory()->create();
    $disk = Disk::factory()->for($user)->create();

    actingAs($user)->postJson(
        uri: action(StoreController::class, [$disk->id]),
        data: []
    )->assertStatus(
        status: Response::HTTP_UNPROCESSABLE_ENTITY,
    )->assertJsonValidationErrors([
        'file',
        'path',
        'name',
    ]);
})->group('api', 'files');
