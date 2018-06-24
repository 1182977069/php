<?php
namespace app\admin\validate;
use think\Validate;
class Role extends Validate{
    protected $rule=[
        'role_name'=>'require|unique:role',
    ];
    protected $message=[
        'role_name.require'=>'角色名必填',
        'role_name.unique'=>'角色名重复了'
    ];
    protected $scene=[
        'add'=>['role_name'],
        'upd'=>['role_name'=>'require']
    ];
}