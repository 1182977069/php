<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Attribute;
use app\admin\model\Type;
class AttributeController extends CommonController{
    public function add(){
        $attrModel=new Attribute();
        if(request()->isPost()){
            $postData=input('post.');
            $postData['attr_values']=trim($postData['attr_values']);
            if($postData['attr_input_type']=='1'){
                $result=$this->validate($postData,'Attribute.listselect',[],true);
            }else{
                $result=$this->validate($postData,'Attribute.add',[],true);
            }
            if($result!==true){
                $this->error(implode(',',$result));
            }
            if($attrModel->save($postData)){
                $this->success('添加成功',url('admin/attribute/index'));
            }else{
                $this->error('添加失败');
            }
        }
        $typeModel=new Type();
        $lists=$typeModel->select();
        return $this->fetch('',[
            'lists'=>$lists
        ]);
    }

    public function index(){
        $attrModel=new Attribute();
        $lists=$attrModel->alias('t1')->join('sh_type t2','t1.type_id=t2.type_id','left')->select()->toArray();
        return $this->fetch('',[
            'lists'=>$lists
        ]);
    }

    public function upd(){
        $attrModel=new Attribute();
        $typeModel=new Type();
        if(request()->isPost()){
            $postData=input('post.');
            $postData['attr_values']=trim($postData['attr_values']);
            if($postData['attr_input_type']=='1'){
                $result=$this->validate($postData,'Attribute.listselect',[],true);
            }else{
                $result=$this->validate($postData,'Attribute.upd',[],true);
            }
            if($result!==true){
                $this->error(implode(',',$result));
            }
            if($attrModel->isUpdate(true)->save($postData)){
                $this->success('添加成功',url('admin/attribute/index'));
            }else{
                $this->error('添加失败');
            }
        }
        $attr_id=input('attr_id');
        $data=$attrModel->find($attr_id);
        $lists=$typeModel->select();
        return $this->fetch('',[
            'data'=>$data,
            'lists'=>$lists
        ]);
    }

    public function ajaxdel(){
        if(request()->isAjax()){
            $attr_id=input('attr_id');
            if(Attribute::destroy($attr_id)){
                return json(['code'=>200,'message'=>'删除成功']);
            }else{
                return json(['code'=>-1,'message'=>'删除失败']);
            }
        }
    }
}