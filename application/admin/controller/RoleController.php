<?php
namespace app\admin\controller;
use app\admin\model\Auth;
use app\admin\model\Role;
use think\Db;
class RoleController extends CommonController{
    public function add(){
        $authModel=new Auth();
        if(request()->isPost()){
            $postData=input('post.');
            $result=$this->validate($postData,'Role.add',[],true);
            if($result!==true){
                $this->error(implode(',',$result));
            }
            $roleModel=new Role();
            if($roleModel->save($postData)){
                $this->success('添加成功',url('admin/role/index'));
            }else{
                $this->error('添加失败');
            }
        }
        $authsData=$authModel->select()->toArray();
        $auths=[];
        foreach ($authsData as $auth){
            $auths[$auth['auth_id']]=$auth;
        }
        $children=[];
        foreach ($authsData as $auth){
            $children[$auth['pid']][]=$auth['auth_id'];
        }
        return $this->fetch('',[
            'auths'=>$auths,
            'children'=>$children
        ]);
    }

    public function index(){
        $sql="SELECT t1.*, GROUP_CONCAT(t2.auth_name SEPARATOR '|') all_auth FROM sh_role t1 LEFT JOIN sh_auth t2 ON FIND_IN_SET(t2.auth_id, t1.auth_id_list) GROUP BY t1.role_id";
        $lists=Db::query($sql);
        return $this->fetch('',[
            'lists'=>$lists
        ]);
    }

    public function upd(){
        $authModel=new Auth();
        $roleModel=new Role();
        $role_id=input('role_id');
        if(request()->isPost()){
            $postData=input('post.');
            $result=$this->validate($postData,'Role.upd',[],true);
            if($result!==true){
                $this->error(implode(',',$result));
            }
            if($roleModel->isUpdate(true)->save($postData)){
                $this->success('修改成功',url('admin/role/index'));
            }else{
                $this->error('修改失败');
            }
        }
        $authDatas=$authModel->select()->toArray();
        $auths=[];
        foreach($authDatas as $auth){
            $auths[$auth['auth_id']]=$auth;
        }
        $children=[];
        foreach($authDatas as $auth){
            $children[$auth['pid']][]=$auth['auth_id'];
        }
        $data=$roleModel->find($role_id);
        return $this->fetch('',[
            'auths'=>$auths,
            'children'=>$children,
            'data'=>$data
        ]);
    }

    public function del(){
        $role_id=input('role_id');
        if(Role::destroy($role_id)){
            $this->success('删除成功',url('admin/role/index'));
        }else{
            $this->error('删除失败');
        }
    }


}