<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Files;
use Illuminate\Support\Facades\Route;

Route::get('{disk}', Files\IndexController::class)->name('index');
Route::post('{disk}', Files\StoreController::class)->name('store');
Route::get('{disk}/{file}', Files\ShowController::class)->name('show');

// delete a file from a disk
// copy a file from one disk to another
// bulk upload files to a specified disk
// bulk delete files from a specified disk
// bulk copy files from one disk to another
