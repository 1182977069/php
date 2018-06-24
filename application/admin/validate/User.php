<?php
namespace app\admin\validate;
use think\Validate;
class User extends Validate{
    protected $rule=[
        'username'=>'require|unique:user',
        'password'=>'require',
        'repassword'=>'require|confirm:password',
        'captcha'=>'require|captcha'
    ];
    protected $message=[
        'username.require'=>'用户名必填',
        'username.unique'=>'用户名重复',
        'password.require'=>'密码必填',
        'repassword.require'=>'请重复密码',
        'repassword.confirm'=>'两次密码不一样',
        'captcha.require'=>'验证码必填',
        'captcha.captcha'=>'验证码错误'
    ];
    protected $scene=[
        'add'=>['username','password','repassword'],
        'upd'=>['username'=>'require|unique:user'],
        'login'=>['username'=>'require','password'=>'require','captcha']
    ];
}