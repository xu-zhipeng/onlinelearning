<?php
namespace app\admin\model;
use think\Model;
use think\Controller;

class IndexModel extends Model
{
    public function index()
    {
        echo "index";
    }

    public function chart()
    {
        echo "chart";
    }

    public function ziliao($res,$data)
    {
        if(empty($data) || !is_array($data)){
            return false;
            }

            $res=db('yonghu')->where('id',$res['id'])->update($data);
            if($res!==false){
                return true;
            }
            else{
                return false;
            }

    }

    public function shezhi($res,$data)
    {
        if(empty($data) || !is_array($data)){
            return false;
            }
            if($res['password']!=md5($data['oldpassword'])){
                return 2;
            }
            $da=array_splice($data,1,1);   //分割数组
            if($da['password']){
                $da['password']=md5($da['password']);
            }
            $res=db('yonghu')->where('id',$res['id'])->update($da);
            if($res!==false){
                return true;
            }
            else{
                return false;
            }

    }







}
