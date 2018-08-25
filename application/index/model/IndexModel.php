<?php
namespace app\index\model;
use think\Model;
use think\Controller;

class IndexModel extends Model
{
     public function login($data)
    {
        $user=db('user')->where('username',$data['username'])->find();
        if($user){
            if($user['password']==md5($data['password'])){
                session('id',$user['id']);
                session('username',$user['username']);
                //var_dump("$Request.session.username");
                return 3; //登录成功
            }
            else{
                return 2; //密码错误
            }
        }
        else{
            return 1;//用户名不存在
        }

    }









}
