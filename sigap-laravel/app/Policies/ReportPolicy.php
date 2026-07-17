<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('user') || $user->hasRole('admin');
    }

    public function view(User $user, Report $report): bool
    {
        return $user->hasRole('admin') || $report->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('user');
    }

    public function update(User $user, Report $report): bool
    {
        return $user->hasRole('admin') || ($user->hasRole('user') && $report->user_id === $user->id);
    }

    public function delete(User $user, Report $report): bool
    {
        return $user->hasRole('admin') || ($user->hasRole('user') && $report->user_id === $user->id);
    }
}
