<?php

namespace LaravelUac;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class UacServiceProvider extends ServiceProvider
{
    protected $routeMiddleware = [        
        'uac.allow' => Middleware\UacAllow::class,
        'uac.deny' => Middleware\UacDeny::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->registerRouteMiddleware();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
            $this->registerSeedsFrom(__DIR__.'/../database/seeds');
        }
    }    

    protected function registerSeedsFrom($path)
    {
        foreach (glob("$path/*.php") as $filename)
        {
            include $filename;
            $classes = get_declared_classes();
            $class = end($classes);

            $command = Request::server('argv', null);
            if (is_array($command)) {
                $command = implode(' ', $command);
                if ($command == "artisan db:seed") {
                    Artisan::call('db:seed', ['--class' => $class]);
                }
            }

        }
    }

    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }
    }
}
