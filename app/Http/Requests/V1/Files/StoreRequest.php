<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Files;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

final class StoreRequest extends FormRequest
{
    /** @return array<string,ValidationRule|array|string> */
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'max:10240'],
            'path' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    public function getUploadedFile(): UploadedFile
    {
        return $this->file('file');
    }
}
