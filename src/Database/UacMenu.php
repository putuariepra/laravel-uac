<?php

namespace LaravelUac\Database;

use Illuminate\Support\Arr;
use LaravelUac\Database\UacRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class UacMenu extends Model
{
    //
    protected $table = 'uac_menu';
    protected $parentColumn = 'parent_id';
    protected $orderColumn = 'order';

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
    
    public function allNodes()
    {
        $orderColumn = DB::getQueryGrammar()->wrap($this->orderColumn);
        $byOrder = $orderColumn.' = 0,'.$orderColumn;

        $self = new static();            
        return $self->orderByRaw($byOrder)->with('roles')->get()->toArray();
    }

    protected function buildNestedArray($user = null, array $nodes = [], $parentId = 0)
    {
        $branch = [];

        if (empty($nodes)) {
            $nodes = $this->allNodes();            
        }

        $visible = 0;
        foreach ($nodes as $node) {
            if ($node[$this->parentColumn] == $parentId) {
                list($children, $visible) = $this->buildNestedArray($user, $nodes, $node[$this->getKeyName()]);                
                $node['children'] = $children;
                
                if (!empty($children)) {
                    $branch[] = $node;                    
                }elseif (empty($children) && (is_null($user) || !is_null($user) && ($user->isAdministrator() || $user->hasRoles(array_column($node['roles'], 'slug'))))) {
                    $branch[] = $node;
                    $visible = 1;
                }
            }
        }

        return [$branch, $visible];
    }    

    public function tree($user = null)
    {
        list($menus, $visible) = $this->buildNestedArray($user);
        return $menus;
    }
}
