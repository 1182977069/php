<?php
namespace app\home\validate;
use think\Validate;
class Member extends Validate{
    //定义验证规则
    protected $rule = [
        'username' => 'require|unique:member',
        'email' => 'require|email',
        //对生成的验证码标识为2的进行验证
        'captcha'=>'require|captcha:2',
        'phone' => 'require|unique:member',
        'password' => 'require',
        'repassword' => 'require|confirm:password',

    ];
    //定义提示信息
    protected $message = [
        'captcha.require' => '验证码必填',
        'captcha.captcha' => '验证码错误',
        'username.require' => '用户名必填',
        'username.unique' => '用户名占用',
        'phone.require' => '手机号必填',
        'phone.unique' => '手机号占用',
        'password.require' => '密码必填',
        'email.require' => '邮箱必填',
        'email.email' => '邮箱格式不正确',
        'repassword.require' => '确认密码必填',
        'repassword.confirm' => '两次密码输入不一致',

    ];
    //定义验证的场景
    protected $scene = [
        'register' => ['username','email','password','repassword','phone'],
        'sms' => ['phone'],
        'login' => ['username'=>"require",'password','captcha'],
        'email' => ['email']
    ];
}