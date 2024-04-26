<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Disks\IndexController;
use App\Models\Disk;
use App\Models\File;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

use Symfony\Component\HttpFoundation\Response;

test('an unauthenticated user cannot list disks', function (): void {
    getJson(
        uri: action(IndexController::class)
    )->assertStatus(
        status: Response::HTTP_UNAUTHORIZED,
    );
})->group('api', 'disks');

test('an authenticated user can get an OK status code', function (): void {
    actingAs(User::factory()->create())->getJson(
        uri: action(IndexController::class),
    )->assertStatus(
        status: Response::HTTP_OK,
    );
})->group('api', 'disks');

test('the response body matches the expected structure', function (): void {
    $user = User::factory()->create();

    Disk::factory()->for($user)->count(3)->create();

    actingAs($user)->getJson(
        uri: action(IndexController::class),
    )->assertJson(
        fn (AssertableJson $json) => $json
            ->count(3)
            ->each(
                fn (AssertableJson $json) => $json
                    ->has('id')
                    ->has('name')
                    ->has('disk')
                    ->has('properties')
                    ->etc()
            )
    );
})->group('api', 'disks');

test('the response can optionally include the user for the disk', function (): void {
    $user = User::factory()->create();

    Disk::factory()->for($user)->create();

    actingAs($user)->getJson(
        uri: action(IndexController::class, [
            'include' => 'user',
        ]),
    )->assertJson(
        fn (AssertableJson $json) => $json
            ->count(1)
            ->each(
                fn (AssertableJson $json) => $json
                    ->has('user')
                    ->has('user.id')
                    ->has('user.name')
                    ->has('user.email')
                    ->has('user.created.human')
                    ->has('user.created.string')
                    ->has('user.created.local')
                    ->has('user.created.timestamp')
                    ->etc()
            )
    );
})->group('api', 'disks');

test('the response can include a list of files', function (): void {
    $user = User::factory()->create();

    $disk = Disk::factory()->for($user)->create();

    File::factory()->for($disk)->count(3)->create();

    actingAs($user)->getJson(
        uri: action(IndexController::class, [
            'include' => 'files',
        ]),
    )->assertJson(
        fn (AssertableJson $json) => $json
            ->each(
                fn (AssertableJson $json) => $json
                    ->has('files')->etc()
            ),
    );
})->group('api', 'disks');

test('a user without a verified email will get an conflict response', function (): void {
    actingAs(User::factory()->create(['email_verified_at' => null]))->getJson(
        uri: action(IndexController::class),
    )->assertStatus(
        status: Response::HTTP_CONFLICT,
    );
})->group('api', 'disks');
