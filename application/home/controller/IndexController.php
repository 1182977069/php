<?php
namespace app\home\controller;
use app\home\model\Goods;
use think\Controller;
use app\home\model\Category;
class IndexController extends Controller{
    public function index(){
        $catModel=new Category();
        $navCats=$catModel->where(['is_show'=>1,'pid'=>0])->select();

        $catData=$catModel->select()->toArray();
        $cats=[];
        foreach($catData as $cat){
            $cats[$cat['cat_id']]=$cat;
        }
        $children=[];
        foreach($catData as $cat){
            $children[$cat['pid']][]=$cat['cat_id'];
        }
        $goodsModel=new Goods();
        $hotGoods=$goodsModel->getTypeGoods('is_hot');
        $newGoods=$goodsModel->getTypeGoods('is_new');
        $bestGoods=$goodsModel->getTypeGoods('is_best');
        $crazyGoods=$goodsModel->getTypeGoods('is_crazy',2);
        $guessGoods=$goodsModel->getTypeGoods('is_guess');
        return $this->fetch('',[
            'navCats'=>$navCats,
            'cats'=>$cats,
            'children'=>$children,
            'bestGoods'=>$bestGoods,
            'crazyGoods'=>$crazyGoods,
            'guessGoods'=>$guessGoods,
            'newGoods'=>$newGoods,
            'hotGoods'=>$hotGoods
        ]);
    }
}