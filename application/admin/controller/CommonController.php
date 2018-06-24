<?php
namespace app\admin\controller;
use think\Controller;
class CommonController extends Controller{
    public function _initialize(){
        if(!session('username')){
            $this->error('你还没有登录',url('admin/public/login'));
        }

        $now_ca=strtolower(request()->controller().'/'.request()->action());
        $visitorAuth=session('visitorAuth');
        if($visitorAuth=='*'||strtolower(request()->controller())=='index'){
            return;
        }else{
            if(!in_array($now_ca,$visitorAuth)){
                if(request()->isAjax()){
                    echo json_encode(['message'=>'无权访问, 请联系管理员']);
                    die();
                }
                exit('访问错误');
            }
        }
    }
}