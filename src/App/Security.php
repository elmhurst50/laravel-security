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

        return in_array($ip, config('security::valid_ips'));
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
        $failed_logins = LoginFail::where('email', $email)->whereBetween('created_at', [Carbon::now()->toDateTimeString(), Carbon::now()->subDay()->toDateTimeString()])->count();

        if($failed_logins >= config('security::max_daily_login_failures')) return true;

        return false;
    }

    /**
     * Locks the users account setting
     * @param $model
     * @param $email
     * @return bool
     */
    public function lockAccount($model, $email)
    {
        $user = $model->where('email', $email)->first();

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
}