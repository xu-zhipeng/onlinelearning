<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\LoginModel;    //引入模型模块

class login extends Controller
{
    public function index()
    {
        if(request()->isPost()){
            $data=input('post.');
            $index=new LoginModel();
            $res=$index->login($data);
            if($res==1){
                $this->error('用户名不存在!');
            }
            if($res==2){
                $this->error('密码错误!');
            }
            if($res==3){
                $this->success('登录成功!',url('index/index'));
            }

            return;
        }
        return view();
    }







}
