<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use SamJoyce777\LaravelSecurity\App\Security;

class IPRestrict
{
    protected $security;

    public function __construct()
    {
        $this->security = new Security();
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

                \Session::flash('error', 'You cant access the sign up form from this IP address.');

                return redirect()->route('login');
        }

        return $next($request);
    }
}
