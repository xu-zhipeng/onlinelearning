<?php
namespace app\admin\model;
use think\Model;
use think\Controller;

class LoginModel extends Model
{
    public function login($data)
    {
        $admin=db('yonghu')->where('name',$data['name'])->find();
        if($admin){
            if($admin['password']==md5($data['password'])){
                session('id',$admin['id']);
                session('name',$admin['name']);
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
