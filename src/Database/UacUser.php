<?php

namespace LaravelUac\Database;

use LaravelUac\Database\UacMenu;
use LaravelUac\Database\UacRole;

trait UacUser
{    
    protected $__menu;

    /**
     * Check if user has permission to pass through.     
     */
    public function accesscontrol($role): bool
    {
        if (empty($role)) {
            return true;
        }

        if ($this->isAdministrator()) {
            return true;
        }

        if (\is_array($role)) {
            return $this->hasRoles($role);    
        }
        return $this->hasRole($role);
    }    

    /**
     * Check if user is administrator.
     *
     * @return mixed
     */
    public function isAdministrator(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is $role.     
     */
    public function hasRole(string $role): bool
    {
        return $this->roles->pluck('slug')->contains($role);
    }

    /**
     * Check if user in $roles.     
     */
    public function hasRoles(array $roles = []): bool
    {
        return $this->roles->pluck('slug')->intersect($roles)->isNotEmpty();
    }    

    /**
     * Relationship roles
     */
    public function roles()
    {
        return $this->belongsToMany(UacRole::class, 'uac_role_users', 'user_id', 'role_id');
    }

    /**
     * User's Menu
     */
    public function menu()
    {
        if (!empty($this->__menu)) {
            return $this->__menu;
        }
        
        $menuModel = new UacMenu;
        return $this->__menu = $menuModel->tree($this);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->roles()->detach();
        });
    }
}
