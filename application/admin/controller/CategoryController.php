<?php
namespace app\admin\controller;
use app\admin\model\Category;
class CategoryController extends CommonController{
    public function add(){
        $catModel=new Category();
        if(request()->isPost()){
            $postData=input('post.');
            $result=$this->validate($postData,'Category.add',[],true);
            if($result!==true){
                $this->error(implode(',',$result));
            }
            if($catModel->save($postData)){
                $this->success('添加成功',url('admin/category/index'));
            }else{
                $this->error('添加失败');
            }
        }
        $lists=$catModel->getSonsCat();
        return $this->fetch('',[
            'lists'=>$lists
        ]);
    }

    public function index(){
        $catModel=new Category();
        $lists=$catModel->getSonsCat();
        return $this->fetch('',[
            'lists'=>$lists
        ]);
    }

    public function upd(){
        $catModel=new Category();
        if(request()->isPost()){
            $postData=input('post.');
            $result=$this->validate($postData,'Category.upd',[],true);
            if($result!==true){
                $this->error(implode(',',$result));
            }
            if($catModel->isUpdate(true)->save($postData)){
                $this->success('添加成功',url('admin/category/index'));
            }else{
                $this->error('添加失败');
            }
        }
        $cat_id=input('cat_id');
        $data=$catModel->find($cat_id);
        $lists=$catModel->getSonsCat();
        return $this->fetch('',[
            'data'=>$data,
            'lists'=>$lists
        ]);
    }

    public function ajaxdel(){
        if(request()->isAjax()){
            $cat_id=input('cat_id');
            if(Category::destroy($cat_id)){
                return json(['code'=>200,'message'=>'删除成功']);
            }else{
                return json(['code'=>-1,'message'=>'删除失败']);
            }
        }
    }
}