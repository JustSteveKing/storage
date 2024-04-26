<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

final readonly class EmailVerificationNotificationController
{
    public function __construct(
        private AuthManager $auth,
    ) {}

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        if ($this->auth->user()->hasVerifiedEmail()) {
            return Redirect::intended(
                default: '/dashboard',
            );
        }

        $this->auth->user()->sendEmailVerificationNotification();

        return new JsonResponse(
            data: ['status' => 'verification-link-sent'],
        );
    }
}
