<?php
namespace app\admin\model;
use think\Model;
class Category extends Model{
    protected $pk='cat_id';

    public function getSonsCat(){
        $datas=$this->select();
        return $this->_getSonsCat($datas);
    }
    private function _getSonsCat($datas,$pid=0,$deep=0){
        static $result=[];
        foreach ($datas as $data){
            if($data['pid']==$pid){
                $data['deep']=$deep;
                $result[$data['cat_id']]=$data;
                $this->_getSonsCat($datas,$data['cat_id'],$deep+1);
            }
        }
        return $result;
    }
}