<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Goods;
use app\home\model\Category;
use think\Db;
class GoodsController extends Controller{
    public function detail(){
        $goods_id=input('goods_id');
        $goods_data=Goods::find($goods_id);

        $catModel=new Category();
        $familyData=$catModel->getFamilyCats($goods_data['cat_id']);

        $goods_data['goods_img']=json_decode($goods_data['goods_img']);
        $goods_data['goods_middle']=json_decode($goods_data['goods_middle']);
        $goods_data['goods_thumb']=json_decode($goods_data['goods_thumb']);

        $singelData=Db::name('goods_attr')
                    ->alias('t1')
                    ->field('t1.*,t2.attr_name')
                    ->join('sh_attribute t2','t1.attr_id=t2.attr_id','left')
                    ->where("t1.goods_id=$goods_id and t2.attr_type=1")
                    ->select();
        $singel_data=[];
        foreach($singelData as $k=>$attr){
            $singel_data[$attr['attr_id']][]=$attr;
        }

        $onlyData=Db::name('goods_attr')
            ->alias('t1')
            ->field('t1.*,t2.attr_name')
            ->join('sh_attribute t2','t1.attr_id=t2.attr_id','left')
            ->where("t1.goods_id=$goods_id and t2.attr_type=0")
            ->select();


        $goodsModel=new Goods();
        $history=$goodsModel->addGoodsToHistory($goods_id);
        $history=implode(',',$history);
        $sql="select * from sh_goods where goods_id in ($history) order by field(goods_id,$history)";
        $historyGoods=Db::query($sql);
        return $this->fetch('',[
            'goods_data'=>$goods_data,
            'familyData'=>$familyData,
            'singel_data'=>$singel_data,
            'onlyData'=>$onlyData,
            'historyGoods'=>$historyGoods
        ]);
    }

    public function test(){
        $cart=new \cart\Cart();
        halt($cart);
    }
}