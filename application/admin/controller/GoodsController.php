<?php
namespace app\admin\controller;
use app\admin\model\Attribute;
use app\admin\model\Category;
use app\admin\model\Goods;
use app\admin\model\Type;
class GoodsController extends CommonController{
    public function add(){
        $goodsModel=new Goods();
        if(request()->isPost()){
            $postData=input('post.');
            $result=$this->validate($postData,'goods.add',[],true);
            if($result!==true){
                $this->error(implode(',',$result));
            }
            $goods_img=$goodsModel->uploadImg();
            if($goods_img){
                $result=$goodsModel->thumbImg($goods_img);
                $postData['goods_middle']=json_encode($result['middle']);
                $postData['goods_thumb']=json_encode($result['small']);
                $postData['goods_img']=json_encode($goods_img);
            }
            if($goodsModel->allowField(true)->save($postData)){
                $this->success('添加成功',url('admin/goods/index'));
            }else{
                $this->error('添加失败');
            }
        }
        $catModel=new Category();
        $cats=$catModel->getSonsCat();
        $types=Type::select();
        return $this->fetch('',[
            'cats'=>$cats,
            'types'=>$types
        ]);
    }

    public function ajaxGetTypeAttr(){
        if(request()->isAjax()){
            $type_id=input('type_id');
            $attrData=Attribute::where('type_id',$type_id)->select();
            return json($attrData);
        }
    }

    public function index(){
        $lists=Goods::select();
        return $this->fetch('',[
            'lists'=>$lists
        ]);
    }

    public function ajaxGetContent(){
        if(request()->isAjax()){
            $goods_id=input('goods_id');
            $goods=Goods::where('goods_id',$goods_id)->find();
            return json(['code'=>200,'goods'=>$goods]);
        }
    }

    public function is_hot(){
        if(request()->isAjax()){
            $goods_id=input('goods_id');
            $is_hot=input('is_hot')==1?0:1;
            $data=[
                'goods_id'=>$goods_id,
                'is_hot'=>$is_hot
            ];
            $goodsModel=new Goods();
            if($goodsModel->isUpdate(true)->save($data)){
                return json(['code'=>200,'message'=>'成功','is_hot'=>$is_hot]);
            }else{
                return json(['code'=>-1,'message'=>'shibai']);
            }
        }
    }
    public function is_sale(){
        if(request()->isAjax()){
            $goods_id=input('goods_id');
            $is_sale=input('is_sale')==1?0:1;
            $data=[
                'goods_id'=>$goods_id,
                'is_sale'=>$is_sale
            ];
            $goodsModel=new Goods();
            if($goodsModel->isUpdate(true)->save($data)){
                return json(['code'=>200,'message'=>'成功','is_sale'=>$is_sale]);
            }else{
                return json(['code'=>-1,'message'=>'shibai']);
            }
        }
    }
    public function is_new(){
        if(request()->isAjax()){
            $goods_id=input('goods_id');
            $is_new=input('is_new')==1?0:1;
            $data=[
                'goods_id'=>$goods_id,
                'is_new'=>$is_new
            ];
            $goodsModel=new Goods();
            if($goodsModel->isUpdate(true)->save($data)){
                return json(['code'=>200,'message'=>'成功','is_new'=>$is_new]);
            }else{
                return json(['code'=>-1,'message'=>'shibai']);
            }
        }
    }
    public function is_best(){
        if(request()->isAjax()){
            $goods_id=input('goods_id');
            $is_best=input('is_best')==1?0:1;
            $data=[
                'goods_id'=>$goods_id,
                'is_best'=>$is_best
            ];
            $goodsModel=new Goods();
            if($goodsModel->isUpdate(true)->save($data)){
                return json(['code'=>200,'message'=>'成功','is_best'=>$is_best]);
            }else{
                return json(['code'=>-1,'message'=>'shibai']);
            }
        }
    }
}