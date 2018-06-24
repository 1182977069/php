<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Goods extends Model{
    protected $pk='goods_id';

    protected static function init()
    {
        Goods::event('before_insert',function($goods){
            $goods['goods_sn']=date('ymdhis').uniqid();
        });
        Goods::event('after_insert',function($goods){
            $postData=input('post.');
            $goodsAttrValue=$postData['goodsAttrValue'];
            $goodsAttrPrice=$postData['goodsAttrPrice'];
            $goods_id=$goods['goods_id'];
            foreach($goodsAttrValue as $attr_id=>$attr_values){
                if(is_array($attr_values)){
                    foreach ($attr_values as $k=>$single_attr_value){
                        $data=[
                            'goods_id'=>$goods_id,
                            'attr_id'=>$attr_id,
                            'attr_value'=>$single_attr_value,
                            'attr_price'=>$goodsAttrPrice[$attr_id][$k],
                            'create_time'=>time(),
                            'update_time'=>time()
                        ];
                        Db::name('goods_attr')->insert($data);
                    }
                }else{
                    $data=[
                        'goods_id'=>$goods_id,
                        'attr_id'=>$attr_id,
                        'attr_value'=>$attr_values,
                        'create_time'=>time(),
                        'update_time'=>time()
                    ];
                    Db::name('goods_attr')->insert($data);
                }
            }
        });
    }

    public function uploadImg(){
        $goods_img=[];
        $files=request()->file('img');
        if($files){
            $condition=['size'=>3*1024*1024,'ext'=>'jpg,png,gif'];
            $uploadDir='./upload/';
            foreach($files as $file){
                $info=$file->validate($condition)->move($uploadDir);
                if($info){
                    $goods_img[]=str_replace('\\','/',$info->getSaveName());
                }
            }
        }
        return $goods_img;
    }

    public function thumbImg($goods_img){
        $middle=[];
        $small=[];
        foreach ($goods_img as $path){
            $path_arr=explode('/',$path);
            $middle_path=$path_arr[0].'/middle_'.$path_arr[1];
            $image=\think\Image::open('./upload/'.$path);
            $image->thumb(350,350,2)->save('./upload/'.$middle_path);
            $middle[]=$middle_path;
        }
        foreach ($goods_img as $path){
            $path_arr=explode('/',$path);
            $small_path=$path_arr[0].'/small_'.$path_arr[1];
            $image=\think\Image::open('./upload/'.$path);
            $image->thumb(50,50,2)->save('./upload/'.$small_path);
            $small[]=$small_path;
        }
        return ['middle'=>$middle,'small'=>$small];
    }
}