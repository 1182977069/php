<?php
namespace app\home\model;
use think\Model;
class Category extends Model{
    protected $pk='cat_id';

    public function getFamilyCats($cat_id){
        $cats=$this->select()->toArray();
        return $this->_getFamilyCats($cats,$cat_id);
    }

    public function _getFamilyCats($cats,$cat_id){
        static $result=[];
        foreach($cats as $k=>$cat){
            if($cat['cat_id']==$cat_id){
                $result[]=$cat;
                unset($cats[$k]);
                $this->_getFamilyCats($cats,$cat['pid']);
            }
        }
        return array_reverse($result);
    }

    public function getSonsCatId($cat_id){
        $cats=$this->select()->toArray();
        return $this->_getSonsCatId($cats,$cat_id);
    }
    private function _getSonsCatId($cats,$cat_id){
        static $result=[];
        foreach($cats as $k=>$cat){
            if($cat['pid']==$cat_id){
                $result[]=$cat['cat_id'];
                unset($cats[$k]);
                $this->_getSonsCatId($cats,$cat['cat_id']);
            }
        }
        return $result;
    }
}