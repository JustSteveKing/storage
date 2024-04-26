<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('user', fn (Request $request) => $request->user());

Route::prefix('v1')->as('v1:')->group(static function (): void {
    Route::middleware(['auth:sanctum', 'verified'])->group(static function (): void {
        Route::prefix('disks')->as('disks:')->group(base_path(
            path: 'routes/api/v1/disks.php',
        ));

        Route::prefix('files')->as('files:')->group(base_path(
            path: 'routes/api/v1/files.php',
        ));
    });
});
