<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Goods;
use think\Db;
use app\home\model\Order;
use app\home\model\OrderGoods;
class OrderController extends Controller{

    public function payMoney(){
        $id=input('id');
        $orderData=Order::find($id);
        if($orderData){
            $this->_payMoney($orderData['order_id'],$orderData['total_price']);
        }else{
            $this->error('支付异常，请重试');
        }
    }

    public function orderinfo(){
        if(!session('member_id')){
            $this->error('请先登录');
        }
        if(request()->isPost()){
            $this->_writeOrder();die;
        }
        $goodsModel=new Goods();
        $cartData=$goodsModel->getCartGoodsData();
        return $this->fetch('',[
            'cartData'=>$cartData
        ]);
    }

    private function _writeOrder(){
        $postData=input('post.');
        $result=$this->validate($postData,'Order',[],true);
        if($result!==true){
            $this->error(implode(',',$result));
        }
        $goodsModel=new Goods();
        $cartData=$goodsModel->getCartGoodsData();
        $total_price=0;
        foreach($cartData as $cart){
            $total_price=($cart['goodsInfo']['goods_price']+$cart['attrInfo']['attr_total_price'])*$cart['goods_number'];
        }
        $orderData=$postData;
        $orderData['total_price']=$total_price;
        $orderData['member_id']=session('member_id');
        $orderData['order_id']=date('Ymd').time().uniqid();

        Db::startTrans();
        try{
            $order=Order::create($orderData);
            if(!$order){
                throw new \Exception('订单表入库失败');
            }
            foreach($cartData as $cart){
                $orderGoodsModel=new OrderGoods();
                $orderGoods=$orderGoodsModel->create([
                    'order_id'=>$orderData['order_id'],
                    'goods_id'=>$cart['goods_id'],
                    'goods_attr_ids'=>$cart['goods_attr_ids'],
                    'goods_number'=>$cart['goods_number'],
                    'goods_price'=>($cart['goodsInfo']['goods_price']+$cart['attrInfo']['attr_total_price'])*$cart['goods_number']
                ]);
                $condition=[
                    'goods_id'=>$cart['goods_id'],
                    'goods_number'=>['>=',$cart['goods_number']]
                ];
                $num=Goods::where($condition)->setDec('goods_number',$cart['goods_number']);
                if(!$orderGoods||!$num){
                    throw new \Exception('订单商品表失败，或超库存');
                }
            }
            Db::commit();
            $cart=new \cart\Cart();
            $cart->clearCart();
        }catch(\Exception $e){
            Db::rollback();
            $this->error($e->getMessage());
        }
        $this->_payMoney($orderData['order_id'],$total_price);
    }

    private function _payMoney($order_id,$total_price,$title='商城支付',$content='支付'){
        $payData=[
            'WIDout_trade_no'=>$order_id,
            'WIDsubject'=>$title,
            'WIDtotal_amount'=>$total_price,
            'WIDbody'=>$content
        ];
        include "../extend/alipay/pagepay/pagepay.php";
    }

    public function returnurl(){
        require_once("../extend/alipay/config.php");
        require_once('../extend/alipay/pagepay/service/AlipayTradeService.php');


        $arr=input('get.');
        $alipaySevice = new \AlipayTradeService($config);
        $result = $alipaySevice->check($arr);
        if($result){

            $out_trade_no = htmlspecialchars($arr['out_trade_no']);
            $trade_no = htmlspecialchars($arr['trade_no']);
            $data=[
                'pay_status'=>1,
                'ali_order_id'=>$trade_no
            ];
            if(Order::where('order_id',$out_trade_no)->update($data)){
                $this->success('支付成功',url('home/order/selfOrder'));
            }else{
                $this->error('支付失败',url('home/index/index'));
            }
        }else{
            echo 'error';
        }
    }

    public function selfOrder(){
        $member_id=session('member_id');
        if(!$member_id){
            $this->error('请先登录在尝试');
        }
        $lists=Order::where('member_id',$member_id)->select();
        return $this->fetch('',[
            'lists'=>$lists
        ]);
    }
}