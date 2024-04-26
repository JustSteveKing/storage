<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Disk;
use App\Models\User;

final class DiskPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->verifiedEmail(
            user: $user,
        );
    }

    public function view(User $user, Disk $disk): bool
    {
        return $this->ownsDisk(
            user: $user,
            disk: $disk,
        );
    }

    public function create(User $user): bool
    {
        return $this->verifiedEmail(
            user: $user,
        );
    }

    public function update(User $user, Disk $disk): bool
    {
        return $this->ownsDisk(
            user: $user,
            disk: $disk,
        );
    }

    public function delete(User $user, Disk $disk): bool
    {
        return $this->ownsDisk(
            user: $user,
            disk: $disk,
        );
    }

    public function upload(User $user, Disk $disk): bool
    {
        return $this->ownsDisk(
            user: $user,
            disk: $disk,
        );
    }

    public function listContents(User $user, Disk $disk): bool
    {
        return $this->ownsDisk(
            user: $user,
            disk: $disk,
        );
    }

    private function verifiedEmail(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    private function ownsDisk(User $user, Disk $disk): bool
    {
        return $user->id === $disk->user_id;
    }
}
