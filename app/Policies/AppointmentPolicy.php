<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    public function view(User $user, Appointment $a): bool { return $user->isAdmin() || $a->user_id === $user->id; }

    public function update(User $user, Appointment $a): bool
    {
        return ($user->isAdmin() || $a->user_id === $user->id) && in_array($a->status, ['requested', 'confirmed'], true);
    }

    public function manage(User $user): bool { return $user->isAdmin(); }
}
