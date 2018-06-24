<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\User;
class PublicController extends Controller{
    public function login(){
        if(request()->isPost()){
            $postData=input('post.');
            $result=$this->validate($postData,'User.login',[],true);
            if($result!==true){
                $this->error(implode(',',$result));
            }
            $userModel=new User();
            if($userModel->checkUser($postData['username'],$postData['password'])){
                $this->redirect('admin/index/index');
            }else{
                $this->error('用户名,密码或权限错误');
            }
        }
        return $this->fetch();
    }

    public function logout(){
        session('username',null);
        $this->redirect('admin/public/login');
    }
}