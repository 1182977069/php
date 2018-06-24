<?php
namespace app\home\model;
use think\Model;
use think\Db;
class Goods extends Model{
    protected $pk='goods_id';

    public function getTypeGoods($type,$limit=5){
        $condition=[
            'is_sale'=>1,
            'is_delete'=>0
        ];
        switch ($type){
            case 'is_crazy':
                $goodsData=$this->where($condition)->order('goods_price asc')->limit($limit)->select();
                break;
            case 'is_guess':
                $goodsData=$this->where($condition)->order('rand()')->limit($limit)->select();
                break;
            default:
                $condition[$type]=1;
                $goodsData=$this->where($condition)->limit($limit)->select();
                break;
        }
        return $goodsData;
    }

    public function addGoodsToHistory($goods_id){
        $history=cookie('history')?cookie('history'):[];
        if($history){
            array_unshift($history,$goods_id);
            $history=array_unique($history);
            if(count($history)>5){
                array_pop($history);
            }
        }else{
            $history[]=$goods_id;
        }
        cookie('history',$history,3600*24*7);
        return $history;
    }

    public function getCartGoodsData(){
        $cart=new \cart\Cart();
        $cartInfo=$cart->getCart();
        $cartData=[];
        foreach($cartInfo as $key=>$goods_number){
            $arr=explode('-',$key);
            $cartData[]=[
                'goods_id'=>$arr[0],
                'goods_attr_ids'=>$arr[1],
                'goods_number'=>$goods_number
            ];
        }
//        return $cartData;

        foreach($cartData as $k=>$data){
            $cartData[$k]['goodsInfo']=Db::name('goods')->find($data['goods_id']);
            $cartData[$k]['attrInfo']=Db::name('goods_attr')
                ->field("sum(t1.attr_price) attr_total_price,group_concat(t2.attr_name,':',t1.attr_value,'￥',t1.attr_price separator '<br>') as singleAttr")
                ->alias('t1')
                ->join('sh_attribute t2','t1.attr_id=t2.attr_id','left')
                ->where('t1.goods_attr_id','in',$data['goods_attr_ids'])
                ->find();
        }

        return $cartData;
    }
}