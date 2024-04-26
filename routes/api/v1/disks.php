<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Disks;
use Illuminate\Support\Facades\Route;

Route::get('/', Disks\IndexController::class)->name('index');

// create a new disk for user
// update disk details
// delete a disk
