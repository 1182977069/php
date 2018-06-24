<?php
namespace app\admin\controller;
use app\admin\model\User;
use app\admin\model\Role;
class UserController extends CommonController{

    public function index(){
        $userModel=new User();
        $lists=$userModel->paginate(2);
        return $this->fetch('',[
            'lists'=>$lists
        ]);
    }

    public function add(){
        if(request()->isPost()){
            $postDate=input('post.');
            $result=$this->validate($postDate,'User.add',[],true);
            if($result!==true){
                $this->error(implode(',',$result));
            }
            $userModel=new User();
            if($userModel->allowField(true)->save($postDate)){
                $this->success('添加成功',url('admin/user/index'));
            }else{
                $this->error('添加失败');
            }
        }
        $roleModel=new Role();
        $roles=$roleModel->select()->toArray();
        return $this->fetch('',[
            'roles'=>$roles
        ]);
    }

    public function del(){
        if(request()->isAjax()){
            $user_id=input('user_id');
            if(User::destroy($user_id)){
                return json(['code'=>'200','message'=>'删除成功']);
            }else{
                return json(['code'=>'-1','message'=>'删除失败']);
            }
        }
    }

    public function upd(){
        $user_id=input('user_id');
        $userModel=new User();
        $list=$userModel->find($user_id);
        if(request()->isPost()){
            $postData=input('post.');
            $result=$this->validate($postData,'User.upd',[],true);
            if($result!==true){
                $this->error('修改失败');
            }
            if($userModel->allowField(true)->isUpdate(true)->save($postData)!==false){
                $this->success('修改成功',url('admin/user/index'));
            }else{
                $this->error('修改失败');
            }
        }
        return $this->fetch('',[
            'list'=>$list
        ]);
    }

    public function ajaxChangeActive(){
        if(request()->isAjax()){
            $user_id=input('user_id');
            $is_active=input('is_active');
            $update_active=$is_active?0:1;
            $data=[
                'is_active'=>$update_active,
                'user_id'=>$user_id
            ];
            $userModel=new User();
            if($userModel->update($data)!==false){
                return json(['status'=>200,'is_active'=>$update_active]);
            }else{
                return json(['status'=>-1,'is_active'=>$update_active]);
            }
        }
    }
}