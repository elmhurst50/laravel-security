<?php namespace SamJoyce777\LaravelSecurity\Providers;

use App\Managers\Images\ImageManager;
use Illuminate\Support\ServiceProvider;

class SecurityServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../Database/Migrations' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../Database/Config' => config_path('vendor.security'),
        ], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
