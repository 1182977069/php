<?php
namespace app\admin\model;
use think\Model;
class User extends Model{
    protected $pk='user_id';

    protected static function init()
    {
        User::event('before_insert',function($user){
            $user['password']=md5($user['password'].config('password_salt'));
        });

        User::event('before_update',function($user){
            if(isset($user['password'])){
                $user['password']=md5($user['password'].config('password_salt'));
            }

        });
    }

    public function checkUser($username,$password){
        $condition=[
            'username'=>$username,
            'password'=>md5($password.config('password_salt'))
        ];
        $userInfo=$this->where($condition)->find();
        if($userInfo){
            if(!$userInfo['is_active']){
                return false;
            }
            session('username',$userInfo['username']);
            $this->writeAuthToSession($userInfo['role_id']);
            return true;
        }else{
            return false;
        }
    }

    public function writeAuthToSession($role_id){
        $roleModel=new Role();
        $authModel=new Auth();
        $row=$roleModel->find($role_id);
        $auth_id_list=$row['auth_id_list'];

        if($auth_id_list=='*'){
            $oneAuth=$authModel->where('pid',0)->select()->toArray();
            foreach ($oneAuth as $k=>$auth){
                $oneAuth[$k]['sonsAuth']=$authModel->where('pid',$auth['auth_id'])->select()->toArray();
            }
            session('visitorAuth','*');
        }else{
            $visitorAuth=[];
            $all_auth=$authModel->where('auth_id','in',$auth_id_list)->select()->toArray();
            $oneAuth=[];
            foreach ($all_auth as $k=>$auth){
                if($auth['pid']==0){
                    $oneAuth[]=$auth;
                }
                $visitorAuth[] = strtolower($auth['auth_c'].'/'.$auth['auth_a']);
            }
            foreach ($oneAuth as $k=>$auth){
                foreach ($all_auth as $kk=>$s_auth){
                    if($s_auth['pid']==$auth['auth_id']){
                        $oneAuth[$k]['sonsAuth'][]=$s_auth;
                    }
                }
            }
            session('visitorAuth',$visitorAuth);
        }
        session('menuAuth',$oneAuth);
    }
}