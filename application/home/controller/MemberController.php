<?php
namespace app\home\controller;
use think\Controller;
class MemberController extends Controller{
    public function qqlogin(){
        include '../extend/qqlogin/API/qqConnectAPI.php';
        $qc=new \QC();
        $qc->qq_login();
    }

    public function qqcallback(){
        include '../extend/qqlogin/API/qqConnectAPI.php';
        $qc=new \QC();
        $token=$qc->qq_callback();
        $openid=$qc->get_openid();
        $qc=new \QC($token,$openid);
        $qqUserInfo=$qc->get_user_info();
        dump($qqUserInfo);
    }
}