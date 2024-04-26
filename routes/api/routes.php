<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('user', fn (Request $request) => $request->user());

Route::prefix('v1')->as('v1:')->group(static function (): void {
    Route::middleware(['auth:sanctum','verified'])->group(static function (): void {
        Route::prefix('disks')->as('disks:')->group(base_path(
            path: 'routes/api/v1/disks.php',
        ));

        Route::prefix('files')->as('files:')->group(static function (): void {
            // get all files for a specified disk
            // upload a new file to a specified disk
            // get details of a specific file
            // delete a file from a disk
            // copy a file from one disk to another
            // bulk upload files to a specified disk
            // bulk delete files from a specified disk
            // bulk copy files from one disk to another
        });
    });
});
