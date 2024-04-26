<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\AuthManager;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

final readonly class VerifyEmailController
{
    public function __construct(
        private AuthManager $auth,
    ) {
    }

    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($this->auth->user()->hasVerifiedEmail()) {
            return Redirect::intended(
                default: config('app.frontend_url') . '/dashboard?verified=1'
            );
        }

        if ($this->auth->user()->markEmailAsVerified()) {
            event(new Verified($this->auth->user()));
        }

        return Redirect::intended(
            default: config('app.frontend_url') . '/dashboard?verified=1'
        );
    }
}
