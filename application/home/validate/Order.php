<?php
namespace app\home\validate;
use think\Validate;
class Order extends Validate{
    protected $rule=[
        'receiver'=>'require',
        'address'=>'require',
        'tel'=>'require',
        'zcode'=>'require'
    ];

    protected $message=[
        'receiver.require'=>'收货人必填',
        'address.require'=>'收货地址必填',
        'tel.require'=>'手机号必填',
        'zcode.require'=>'邮编必填'
    ];
}