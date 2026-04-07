<?php


namespace App\Traits;

trait CommonScopes
{
    public function scopeStatus($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeDateRange($query, $from = null, $to = null)
    {
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        return $query;
    }

    public function scopeUserData($query)

    {
        $user = auth()->user();

        if (!$user) {
            return $query->whereNull('id');
        }

        if ($user->is_admin == 1) {
            return $query;
        }

        return $query->where('user_id', $user->id);
    }

}
