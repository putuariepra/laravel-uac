<?php

namespace LaravelUac\Middleware;

use Closure;
use Illuminate\Support\Str;
use LaravelUac\AccessControl;

class UacDeny
{
    protected $middlewarePrefix = 'auth.deny:';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($middleware = collect($request->route()->middleware())->first(function ($middleware) {
            return Str::startsWith($middleware, $this->middlewarePrefix);
        })) {            
            $args = explode(',', str_replace($this->middlewarePrefix, '', $middleware));
            AccessControl::deny($args);
        }
        
        return $next($request);
    }
}
