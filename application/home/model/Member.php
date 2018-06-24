<?php
namespace app\home\model;
use think\Model;
class Member extends Model{
    protected $pk='member_id';

    protected static function init(){
        Member::event('before_insert',function($mem){
            if(isset($mem['password'])){
                $mem['password']=md5($mem['password'].config('password_salt'));
            }
        });

        Member::event('before_update',function($mem){
            $mem['password']=md5($mem['password'].config('password_salt'));
        });
    }

    public function checkUser($username,$password){
        $condition=[
            'username'=>$username,
            'password'=>md5($password.config('password_salt'))
        ];
        $userInfo=$this->where($condition)->find();
        if($userInfo){
            session('home_username',$userInfo['username']);
            session('member_id',$userInfo['member_id']);
            return true;
        }else{
            return false;
        }
    }
}