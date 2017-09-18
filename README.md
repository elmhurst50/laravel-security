# Laravel Security Pacakge

This package adds some checks to be added to your middleware routes to add some additional security for the backend of your app.

    1. Restrict the authorised user to allowed IP address
    2. If not allowed IP, then a cookie is required on the device which is emailed to the user
    3. Lock users account if too many attempts have been made

### Installing

Download or or use composer, currently not on packagist.org

Add the service provider to your config/app.php file
```
 SamJoyce777\LaravelSecurity\Providers\SecurityServiceProvider::class,
```

Then we need to publish the migrations and config files
```
 php artisan vendor:publish --provider="SamJoyce777\LaravelSecurity\SecurityServiceProvider"
```

Then add the middleware to the required section in the middleware kernal
```
 'security' => \SamJoyce777\LaravelSecurity\Http\Middleware\Security::class
```

Laravel Security adds a cookie, you need to have an exception in the Middleware\EncryptCookies.php
```
 'laravel-security'
```



