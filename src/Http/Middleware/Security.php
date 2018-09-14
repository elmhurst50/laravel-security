<?php namespace SamJoyce777\LaravelSecurity\Http\Middleware;

use Closure;

class Security
{
    protected $security;

    public function __construct()
    {
        $this->security = new \SamJoyce777\LaravelSecurity\App\Security();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (\Auth::user()->restrict_ip && !$this->security->checkAuthorizedIP()) {
            \Auth::logout();

            abort(403, 'Access denied due to non-permitted IP Address');
        }

        /**
        if (!$this->security->checkAuthorizedIP() && !$this->security->hasSecurityCookie($request)) {

            $this->security->sendCookieEmail(\Auth::user()->email);

            \Auth::logout();

            abort(403, 'You do not have clearance on this device, please check your email.');
        }
        */

        if ($this->security->isUserLocked(\Auth::user())) {
            \Auth::logout();

            abort(403, 'Access denied due account has been security locked, please see system administrator');
        }

        return $next($request);
    }
}
