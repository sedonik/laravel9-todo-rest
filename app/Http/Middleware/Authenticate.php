<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Illuminate\Support\Facades\Auth;

/**
 * class Authenticate
 */
class Authenticate extends Middleware
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * Authenticate constructor.
     *
     * @param Session $session
     */
    public function __construct(
        Session $session
    ) {
        $this->session = $session;
    }

    /**
     * @param $request
     * @param Closure $next
     * @param ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $credentials = $request->only('email', 'password');

        if(!Auth::once($credentials)) {
            $headers = array('WWW-Authenticate' => 'Basic');
            return response('Password is not correct', 401, $headers);
        }

        $user = Auth::getUser();

        $this->session->set('user_id', $user->id);

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
