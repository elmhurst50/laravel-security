<?php namespace SamJoyce777\LaravelSecurity\Http\Controllers;

use App\Http\Controllers\Controller;

class SecurityController extends Controller
{
    public function applyCookie($public_key)
    {
        if ($public_key == config('security.cookie_public_key')) {
            return response()->view('security::cookie')->withCookie(cookie()->forever('laravel-security', config('security.cookie_private_key')));
        } else {
            abort(403, 'Security confirmation denied');
        }
    }
}