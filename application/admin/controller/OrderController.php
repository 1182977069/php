<?php
namespace app\admin\controller;
use app\admin\model\Order;
class OrderController extends CommonController{
    public function index(){
        $keyword=trim(input('keyword'));
        $where='';
        if($keyword){
            $where.="receiver like '%{$keyword}%' or tel like '%{$keyword}%' or order_id like '%{$keyword}%' or address like '%{$keyword}%'";
        }
        $lists=Order::alias('t1')
            ->field('t1.*,t2.username')
            ->where($where)
            ->join('sh_member t2','t1.member_id=t2.member_id','left')
            ->paginate(3);

        if(request()->isAjax()){
            $result=[
                'pagelist'=>$lists->rander(),
                'tbody'=>$this->fetch('order/tbody',['lists'=>$lists])
            ];
            return json($result);
        }
        return $this->fetch('',[
            'lists'=>$lists
        ]);
    }

    public function upd(){
        if(request()->isPost()){
            $postData=input('post.');
            $result=$this->validate($postData,'Order.wuliu',[],true);
            if($result!==true){
                $this->error(implode(',',$result));
            }
            $orderModel=new Order();
            $postData['send_status']=1;
            if($orderModel->isUpdate(true)->save($postData)!==false){
                $this->success('分配物流成功',url('admin/order/index'));
            }else{
                $this->error('分配失败');
            }
        }
        $id=input('id');
        $data=Order::find($id);
        return $this->fetch('',[
            'data'=>$data
        ]);
    }

    public function queryWuliu(){
        if(request()->isAjax()){
            $key=config('wuliu_key');
            $company=input('company');
            $number=input('number');
            $url="http://www.kuaidi100.com/applyurl?key={$key}&com={$company}&nu={$number}&show=0";
            echo file_get_contents($url);
        }
    }
}