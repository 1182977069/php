<?php
namespace app\admin\validate;
use think\Validate;
class Type extends Validate{
    protected $rule=[
        'type_name'=>'require|unique:type',
    ];
    protected $message=[
        'type_name.require'=>'类型名称必填',
        'type_name.unique'=>'类型名称不能重复',
    ];
    protected $scene=[
        'add'=>['type_name'],
        'upd'=>['type_name'=>'require']
    ];
}