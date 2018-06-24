<?php
namespace app\admin\controller;
use app\admin\model\Attribute;
use app\admin\model\Type;
class TypeController extends CommonController
{
    public function add(){
        $typeModel=new Type();
        if(request()->isPost()){
            $postData=input('post.');
            $postData['mark']=trim($postData['mark']);
            $result=$this->validate($postData,'Type.add',[],true);
            if($result!==true){
                $this->error(implode(',',$result));
            }
            if($typeModel->save($postData)){
                $this->success('添加成功',url('admin/type/index'));
            }else{
                $this->error('添加失败');
            }
        }
        return $this->fetch();
    }

    public function index(){
        $typeModel=new Type();
        $lists=$typeModel->select()->toArray();
        return $this->fetch('',[
            'lists'=>$lists
        ]);
    }

    public function upd(){
        $type_id=input('type_id');
        $typeModel=new Type();
        if(request()->isPost()){
            $postData=input('post.');
            $postData['mark']=trim($postData['mark']);
            $result=$this->validate($postData,'Type.upd',[],true);
            if($result!==true){
                $this->error(implode(',',$result));
            }
            if($typeModel->isUpdate(true)->save($postData)){
                $this->success('添加成功',url('admin/type/index'));
            }else{
                $this->error('添加失败');
            }
        }
        $data=$typeModel->find($type_id);
        return $this->fetch('',[
            'data'=>$data
        ]);
    }

    public function ajaxDel(){
        if(request()->isAjax()){
            $type_id=input('type_id');
            if(Type::destroy($type_id)){
                return json(['code'=>200,'message'=>'删除成功']);
            }else{
                return json(['code'=>-1,'message'=>'删除失败']);
            }
        }
    }

    public function getAttr(){
        $type_id=input('type_id');
        $lists=Attribute::alias('t1')->field('t1.*,t2.type_name')->join('sh_type t2','t1.type_id=t2.type_id','left')->where('t1.type_id',$type_id)->select();
        return $this->fetch('',[
            'lists'=>$lists
        ]);
    }
}