<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Disks;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateRequest extends FormRequest
{
    /** @return array<string,ValidationRule|array|string> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'properties' => ['array'],
        ];
    }
}
