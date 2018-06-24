<?php
namespace app\home\controller;
use app\home\model\Member;
use think\Controller;
class PublicController extends Controller{
    public function logout(){
        session('member_id',null);
        session('home_username',null);
        $this->redirect('home/index/index');
    }

    public function login(){
        if(request()->isPost()){
            $postData=input('post.');
            $result=$this->validate($postData,'Member.login',[],true);
            if($result!==true){
                $this->error(implode(',',$result));
            }
            $memModel=new Member();
            $status=$memModel->checkUser($postData['username'],$postData['password']);
            if($status){
                $this->redirect('home/index/index');
            }else{
                $this->error('用户名密码错误');
            }
        }
        return $this->fetch();
    }

    public function register(){
        if(request()->isPost()){
            $postData=input('post.');
            $result=$this->validate($postData,'Member.register',[],true);
            if($result!==true){
                $this->error(implode(',',$result));
            }
            if(md5($postData['phoneCaptcha'].config('sms_salt'))!==cookie('sms')){
                $this->error('手机验证码输入错误');
            }
            $memModel=new Member();
            if($memModel->allowField(true)->save($postData)){
                $this->success('注册成功',url('home/public/login'));
            }else{
                $this->error('注册失败');
            }
        }
        return $this->fetch();
    }

    public function sendSms(){
        if(request()->isAjax()){
            $phone=input('phone');
            $result=$this->validate(['phone'=>$phone],'Member.sms',[]);
            if($result!==true){
                return json(['code'=>-1,'message'=>$result]);
            }
            $rand=mt_rand(1000,9999);
            $sms=md5($rand.config('sms_salt'));
            cookie('sms',$sms,300);
            return sendSms($phone,array($rand,5),'1');
        }
    }

    public function qqcallback(){
        include '../extend/qqlogin/API/qqConnectAPI.php';
        $qc=new \QC();
        $token=$qc->qq_callback();
        $openid=$qc->get_openid();
        $qc=new \QC($token,$openid);
        $userInfo=Member::where('openid',$openid)->find();
        if($userInfo){
            session('home_username',$userInfo['username']?:$userInfo['nickname']);
            session('member_id',$userInfo['member_id']);
            $this->redirect('home/index/index');
        }else{
            $qqUserInfo=$qc->get_user_info();
            $data=['openid'=>$openid,'nickname'=>$qqUserInfo['nickname']];
            $member=Member::create($data);
            session('home_username',$member['nickname']);
            session('member_id',$member['member_id']);
            $this->redirect('home/index/index');
        }
    }

    public function findpassword(){
        if(request()->isAjax()){
            $email=input('email');
            $result=$this->validate(['email'=>$email],'Member.email',[],true);
            if($result!==true){
                return json(['code'=>-1,'message'=>implode(',',$result)]);
            }
            if($userInfo=Member::where('email',$email)->find()){
                $title='修改密码';
                $time=time();
                $member_id=$userInfo['member_id'];
                $hash=md5($member_id.$time.config('email_salt'));
                $href=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/home/public/updpwd/member_id/".$userInfo['member_id'].'/time/'.$time.'/hash/'.$hash;
                $content="<a href='{$href}'>点击找回密码</a>";

                if(sendEmail($email,$title,$content)){
                    return json(['code'=>200,'message'=>'邮件发送成功']);
                }else{
                    return json(['code'=>-3,'message'=>'邮件发送失败,请联系管理员']);
                }
            }else{
                return json(['code'=>-2,'message'=>'邮箱不存在']);
            }
        }
        return $this->fetch();
    }

    public function updpwd(){
        if(request()->isAjax()){
            $postData=input('post.');
            $memModel=new Member();
            if($memModel->isUpdate(true)->allowField(true)->save($postData)!==false){
                return json(['code'=>200,'message'=>'更新成功，转到登录页面']);
            }else{
                return json(['code'=>-1,'message'=>'更新失败']);
            }
        }

        $member_id=input('member_id');
        $oldtime=input('time');
        $hash=input('hash');
        if(md5($member_id.$oldtime.config('email_salt'))!==$hash){
            exit('无效的链接地址');
        }
        if($oldtime+120<time()){
            exit('没用了');
        }
        return $this->fetch('',['member_id'=>$member_id]);
    }

}