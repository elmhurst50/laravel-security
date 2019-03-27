<?php namespace SamJoyce777\LaravelSecurity\Providers;

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
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/../Http/Routes/routes.php';
        }

        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'security');

        $this->publishes([
            __DIR__.'/../Database/Migrations' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../Config/security.php' => config_path('security.php'),
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
