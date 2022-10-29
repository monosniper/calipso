<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

Trait AuthUserWithCountsController
{
    protected $users;

    public function __construct(User $users)
    {
        $this->users = $users;
    }

    public function getAuthUserWithCounts($find=true) {
        $user = $this->users->withCount(['lots', 'orders']);

        return $find ? $user->findOrFail(auth()->id()) : $user;
    }
}
