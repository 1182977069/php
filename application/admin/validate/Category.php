<?php
namespace app\admin\validate;
use think\Validate;
class Category extends Validate{
    protected $rule=[
        'cat_name'=>'require|unique:category',
        'pid'=>'require'
    ];
    protected $message=[
        'cat_name.require'=>'分类名称必填',
        'cat_name.unique'=>'分类名称重复',
        'pid.require'=>'请选择一个父类'
    ];
    protected $scene=[
        'add'=>['cat_name','pid'],
        'add'=>['cat_name','pid']
    ];
}