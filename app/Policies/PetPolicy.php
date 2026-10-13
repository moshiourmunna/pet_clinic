<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\User;

class PetPolicy
{
    public function update(User $user, Pet $pet): bool { return $user->isAdmin() || $pet->user_id === $user->id; }
    public function delete(User $user, Pet $pet): bool { return $this->update($user, $pet); }
}
