<?php
namespace app\admin\controller;
use app\admin\model\Auth;
class AuthController extends CommonController{
    public function add(){
        $authModel=new Auth();
        if(request()->isPost()){
            $postData=input('post.');
            if($postData['pid']==0){
                $result=$this->validate($postData,'Auth.ding',[],true);
            }else{
                $result=$this->validate($postData,'Auth.add',[],true);
            }
            if($result!==true){
                $this->error(implode(',',$result));
            }
            if($authModel->save($postData)){
                $this->success('添加成功',url('admin/auth/index'));
            }else{
                $this->error('添加失败');
            }
        }
        $auths=$authModel->getAuthsSon();
        return $this->fetch('',[
            'auths'=>$auths
        ]);
    }

    public function index(){
        $authModel=new Auth();
        $lists=$authModel->getAuthsSon();
        return $this->fetch('',[
            'lists'=>$lists
        ]);
    }

    public function upd(){
        $authModel=new Auth();
        $auth_id=input('auth_id');
        if(request()->isPost()){
            $postData=input('post.');
            if($postData['pid']==0){
                $result=$this->validate($postData,'Auth.upd',[],true);
            }else{
                $result=$this->validate($postData,'Auth.upd',[],true);
            }
            if($result!==true){
                $this->error(implode(',',$result));
            }
            if($authModel->isUpdate(true)->save($postData)){
                $this->success('修改成功',url('admin/auth/index'));
            }else{
                $this->error('修改失败');
            }
        }
        $list=$authModel->find($auth_id);
        $auths=$authModel->getAuthsSon();
        return $this->fetch('',[
            'list'=>$list,
            'auths'=>$auths
        ]);
    }

    public function del(){
        $auth_id=input('auth_id');
        $authModel=new Auth();
        if($authModel->where('pid',$auth_id)->find()){
            $this->error('当前权限有子权限，无法删除');
        }
        if(Auth::destroy($auth_id)){
            $this->success('删除成功',url('admin/auth/index'));
        }else{
            $this->error('删除失败');
        }

    }
}