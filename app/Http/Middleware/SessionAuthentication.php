<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $email = $request->session()->get('email');
        $user_id = $request->session()->get('user_id');
        $last_activity = $request->session()->get('last_activity', time());

        $session_timeout = 3600; // 60 minutes timeout

        // Check if session has expired
        if (time() - $last_activity > $session_timeout) {
            $request->session()->flush(); // Clear all session data
            return redirect('/login')->with([
                'message' => 'Session expired. Please log in again.',
                'status' => false,
                'error' => 'Session timeout'
            ]);
        }

        if (!$email || !$user_id) {
            return redirect('/login')->with([
                'message' => 'Unauthorized access. Please log in.',
                'status' => false,
                'error' => 'No session found'
            ]);
        }

        $request->session()->put('last_activity', time());

        $request->headers->set('email', $email);
        $request->headers->set('id', $user_id);

        return $next($request);
    }
}
