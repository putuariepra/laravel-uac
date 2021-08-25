<?php

namespace LaravelUac\Database;

use LaravelUac\Database\UacRole;
use Illuminate\Database\Eloquent\Model;

class UacMenu extends Model
{
    //
    protected $table = 'uac_menu';

    public function roles()
    {        
        return $this->belongsToMany(UacRole::class, 'uac_role_menu', 'menu_id', 'role_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->roles()->detach();
        });
    }
}
