<?php
namespace app\admin\model;
use think\Model;
class Role extends Model{
    protected $pk='role_id';
    protected static function init()
    {
        Role::event('before_insert',function($role){
//            halt($role);
            $role['auth_id_list']=implode(',',$role['auth_id_list']);
        });
        Role::event('before_update',function($role){
//            halt($role);
            $role['auth_id_list']=implode(',',$role['auth_id_list']);
        });
    }
}