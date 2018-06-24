<?php
namespace app\admin\validate;
use think\Validate;
class Goods extends Validate{
    protected $rule=[
        'goods_name'=>'require|unique:goods',
        'goods_price'=>'require',
        'goods_number'=>'require|regex:\d+',
        'cat_id'=>'require'
    ];
    protected $message=[
        'goods_name.require'=>'商品名必填',
        'goods_name.unique'=>'商品名重复',
        'goods_price.require'=>'价格必填',
        'goods_number.require'=>'商品数量必填',
        'goods_number.regex'=>'商品数量不能少于0',
        'cat_id.require'=>'请选择一个商品分类'
    ];
    protected $scene=[
        'add'=>['goods_name','goods_price','goods_number','cat_id']
    ];
}