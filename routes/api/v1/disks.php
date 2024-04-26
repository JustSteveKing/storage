<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Disks;
use Illuminate\Support\Facades\Route;

Route::get('/', Disks\IndexController::class)->name('index');
Route::post('/', Disks\StoreController::class)->name('store');
Route::put('{disk}', Disks\UpdateController::class)->name('update');
Route::delete('{disk}', Disks\DeleteController::class)->name('delete');
// delete a disk
