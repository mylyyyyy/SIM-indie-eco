<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use App\Models\LoginHistory;

class LogSuccessfulLogin
{
    public function __construct(public Request $request) {}

    public function handle(Login $event)
    {
        LoginHistory::create([
            'user_id' => $event->user->id,
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'login_at' => now(),
        ]);
    }
}