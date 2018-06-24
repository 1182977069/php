<?php
namespace app\admin\model;
use think\Model;
class Auth extends Model{
    protected $pk='auth_id';

    public function getAuthsSon(){
        $data=$this->select();
        return $this->_getAuthsSon($data);
    }
    private function _getAuthsSon($data,$pid=0,$deep=0){
        static $result=[];
        foreach ($data as $auth){
            if($auth['pid']==$pid){
                $auth['deep']=$deep;
                $result[$auth['auth_id']]=$auth;
                $this->_getAuthsSon($data,$auth['auth_id'],$deep+1);
            }
        }
        return $result;
    }
}