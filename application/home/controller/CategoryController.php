<?php
namespace app\home\controller;
use app\home\model\Category;
use app\home\model\Goods;
use think\Controller;
class CategoryController extends Controller{

    public function index(){
        $goodsModel=new Goods();
        $hotGoods=$goodsModel->where('is_hot',1)->select();

        $cat_id=input('cat_id');
        $catModel=new Category();
        $familyCats=$catModel->getFamilyCats($cat_id);

        $catsData=$catModel->select()->toArray();
        $cats=[];
        foreach($catsData as $cat){
            $cats[$cat['cat_id']]=$cat;
        }
        $children=[];
        foreach($catsData as $cat){
            $children[$cat['pid']][]=$cat['cat_id'];
        }

        $sonsCatsId=$catModel->getSonsCatId($cat_id);
        $sonsCatsId[]=intval($cat_id);
        $condition=[
            'is_sale'=>['eq',1],
            'is_delete'=>0,
            'cat_id'=>['in',implode(',',$sonsCatsId)]
        ];
        $catsGoods=Goods::where($condition)->select();
        return $this->fetch('',[
            'familyCats'=>$familyCats,
            'cats'=>$cats,
            'children'=>$children,
            'catsGoods'=>$catsGoods,
            'hotGoods'=>$hotGoods
        ]);
    }
}