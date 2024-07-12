<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LogLoginAttempt
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($request->isMethod('post') && $request->is('login')) {
            $email = $request->input('email');
            $status = Auth::check() ? 'success' : 'failure';

            DB::table('login_logs')->insert([
                'email' => $email,
                'login_time' => now(),
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $response;
    }
}
