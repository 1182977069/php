<?php
namespace app\admin\validate;
use think\Validate;
class Attribute extends Validate{
    protected $rule=[
        'attr_name'=>'require|unique:attribute',
        'type_id'=>'require',
        'attr_values'=>'require'
    ];
    protected $message=[
        'attr_name.require'=>'属性名称必填',
        'attr_name.unique'=>'属性名重复',
        'type_id.require'=>'必须选择商品类型',
        'attr_values.require'=>'属性值必填'
    ];
    protected $scene=[
        'add'=>['attr_name','type_id'],
        'upd'=>['attr_name','type_id'],
        'listselect'=>['attr_name','type_id','attr_values']
    ];
}