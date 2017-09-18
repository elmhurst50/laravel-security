<?php namespace SamJoyce777\LaravelSecurity\App;
use Carbon\Carbon;
use SamJoyce777\LaravelSecurity\Models\LoginFail;

/**
 * Main class to check security
 * Class Security
 * @package SamJoyce777\LaravelSecurity\App
 */
class Security{

    /**
     * Returns weather or not the IP user has provided is allowed
     * @return bool
     */
    public function checkAuthorizedIP()
    {
        $ip = getenv('HTTP_CLIENT_IP') ?:
            getenv('HTTP_X_FORWARDED_FOR') ?:
                getenv('HTTP_X_FORWARDED') ?:
                    getenv('HTTP_FORWARDED_FOR') ?:
                        getenv('HTTP_FORWARDED') ?:
                            getenv('REMOTE_ADDR');

        return in_array($ip, config('vendor.security.valid_ips'));
    }

    /**
     * Records the attempted login so they can be monitored
     * @param $email
     * @param $attempted_password
     */
    public function recordFailedLogin($email, $attempted_password)
    {
        LoginFail::create(['email' => $email, 'attempted_password' => $attempted_password]);
    }

    /**
     * Is the amount of failed logins higher than configured
     * @param $email
     * @return bool
     */
    public function shouldAccountBeLocked($email)
    {
        $failed_logins = LoginFail::where('email', $email)->whereBetween('created_at', [Carbon::now()->subDay()->toDateTimeString(), Carbon::now()->toDateTimeString()])->count();

        if($failed_logins >= config('vendor.security.max_daily_login_failures')) return true;

        return false;
    }

    /**
     * Returns where of not the user account is locked
     * @param $user_model
     * @return mixed
     */
    public function isUserLocked($user_model)
    {
        return $user_model->login_locked;
    }

    /**
     * Locks the users account setting
     * @param $model
     * @param $email
     * @return bool
     */
    public function lockAccount($user_model, $email)
    {
        $user = $user_model->where('email', $email)->first();

        if(!$user) return false;

        $user->update(['login_locked' => true]);

        return true;
    }

    /**
     * Unlocks the users account setting
     * @param $email
     * @param $user_model
     * @return bool
     */
    public function unlockAccount($email, $user_model)
    {
        $user = $user_model->where('email', $email)->first();

        if(!$user) return false;

        $user->update(['login_locked' => false]);

        return true;
    }

    /**
     * This checks to see if request has a valid security cookie
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    public function hasSecurityCookie(\Illuminate\Http\Request $request)
    {
        return $request->cookie('laravel-security') == config('vendor.security.cookie_private_key');
    }

    public function sendCookieEmail($email)
    {
        try{
            \Mail::send('security::email-cookie', ['key' => config('vendor.security.cookie_public_key')], function($message) use ($email){
                $message->to($email);
            });
        }catch(\Exception $e){dd($e->getMessage());
            \Log::debug('Security cookie email could not be sent to '. $email);
        }
    }
}