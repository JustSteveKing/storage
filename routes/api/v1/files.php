<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Files;
use Illuminate\Support\Facades\Route;

// get all files for a specified disk
Route::get('{disk}', Files\IndexController::class)->name('index');
// upload a new file to a specified disk
// get details of a specific file
// delete a file from a disk
// copy a file from one disk to another
// bulk upload files to a specified disk
// bulk delete files from a specified disk
// bulk copy files from one disk to another
