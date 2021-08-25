<?php

namespace LaravelUac;

use Illuminate\Support\Facades\Auth;

class AccessControl
{
    /**
     * Check permission.     
     */
    public static function check($roles)
    {
        return Auth::user()->accesscontrol($roles);
    }

    /**
     * Roles allowed to access.          
     */
    public static function allow($roles)
    {        
        if (!Auth::user()->accesscontrol($roles)) {
            static::error();
        }
    }
    
    /**
     * Roles denied to access.     
     */
    public static function deny($roles)
    {        
        if (Auth::user()->accesscontrol($roles)) {
            static::error();
        }
    }

    /**
     * Send error response page.
     */
    public static function error()
    {
        abort(403);
    }

    /**
     * If current user is administrator.     
     */
    public static function isAdministrator()
    {
        return Auth::user()->isAdministrator();
    }
}
