<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Disks;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreRequest extends FormRequest
{
    /** @return array<string,ValidationRule|array|string> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'disk' => ['required', 'string', Rule::in(
                values: array_keys((array) config('filesystems.disks')),
            )],
            'properties' => ['nullable', 'array'],
        ];
    }
}
