<?php

namespace LaravelUac\Database;

use LaravelUac\Database\UacMenu;
use Illuminate\Database\Eloquent\Model;

class UacRole extends Model
{
    //
    protected $table = 'uac_roles';

    public function users()
    {        
        return $this->belongsToMany(config('auth.providers.users.model'), 'uac_role_users', 'role_id', 'user_id');
    }

    public function menus()
    {        
        return $this->belongsToMany(UacMenu::class, 'uac_role_menu', 'role_id', 'menu_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->users()->detach();
            $model->menus()->detach();
        });
    }
}
