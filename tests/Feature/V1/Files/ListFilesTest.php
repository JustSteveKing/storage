<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Files\IndexController;
use App\Models\Disk;
use App\Models\File;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

use Symfony\Component\HttpFoundation\Response;

test('an unauthenticated user cannot list files from a disk', function (): void {
    $disk = Disk::factory()->create();

    getJson(
        uri: action(IndexController::class, [$disk->id])
    )->assertStatus(
        status: Response::HTTP_UNAUTHORIZED
    );
})->group('api', 'files');

test('an unverified user cannot list files from a disk', function (): void {
    $user = User::factory()->create(['email_verified_at' => null]);
    $disk = Disk::factory()->create();

    actingAs($user)->getJson(
        uri: action(IndexController::class, [$disk->id])
    )->assertStatus(
        status: Response::HTTP_CONFLICT,
    );
})->group('api', 'files');

test('an authenticated user cannot list files from a disk they do not own', function (): void {
    $user = User::factory()->create();
    $disk = Disk::factory()->create();

    actingAs($user)->getJson(
        uri: action(IndexController::class, [$disk->id])
    )->assertStatus(
        status: Response::HTTP_FORBIDDEN,
    );
})->group('api', 'files');

test('an authenticated user can list files from a disk they own', function (): void {
    $user = User::factory()->create();
    $disk = Disk::factory()->for($user)->create();

    actingAs($user)->getJson(
        uri: action(IndexController::class, [$disk->id])
    )->assertStatus(
        status: Response::HTTP_OK,
    );
})->group('api', 'files');

test('an authenticated user gets the correct response payload from a disk they own', function (): void {
    $user = User::factory()->create();
    $disk = Disk::factory()->for($user)->create();
    File::factory()->for($disk)->count(3)->create();

    actingAs($user)->getJson(
        uri: action(IndexController::class, [$disk->id])
    )->assertJson(
        fn (AssertableJson $json) => $json
            ->count(3)
            ->each(
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
                    ->etc(),
            ),
    );
})->group('api', 'files');

test('an authenticated user gets the correct response payload from a disk they own with the disk', function (): void {
    $user = User::factory()->create();
    $disk = Disk::factory()->for($user)->create();
    File::factory()->for($disk)->count(3)->create();

    actingAs($user)->getJson(
        uri: action(IndexController::class, [$disk->id, 'include' => 'disk'])
    )->assertJson(
        fn (AssertableJson $json) => $json
            ->count(3)
            ->each(
                fn (AssertableJson $json) => $json
                    ->has('disk.id')
                    ->has('disk.name')
                    ->has('disk.disk')
                    ->has('disk.properties')
                    ->etc(),
            ),
    );
})->group('api', 'files');
