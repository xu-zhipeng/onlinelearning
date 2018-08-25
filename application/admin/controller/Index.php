<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\IndexModel;    //引入模型模块

class Index extends Controller
{
    public function _initialize(){
        if(!session('id') || !session('name')){
            $this->error('您尚未登录系统',url('login/index'));
        }
    }
    public function index()
    {
        return view();
    }

    public function chart()
    {
        return view();
    }

    public function logout(){
        session(null);
        $this->success('退出系统成功！',url('login/index'));
    }

    public function ziliao($id){
        if($id){
            $res=db('yonghu')->find($id);
        }
        else{
            $this->error('非法进入!');
        }
        if(request()->isPost()){
            $data=input('post.');
            $index=new IndexModel();
            $res=$index->ziliao($res,$data);
            if($res!=false){
                $this->success('修改成功!',url('index/index'));
            }
            else{
                $this->error('修改失败!');
            }

            return;
        }

        $this->assign('vo',$res);
        return view();
    }

    public function shezhi($id){
        $repasswordErr = "<small>6-16位密码，区分大小写，不能用空格</small>";
        if($id){
            $res=db('yonghu')->find($id);
        }
        else{
            $this->error('非法进入!');
        }
        if(request()->isPost()){
           if ($_POST['password']!=$_POST['repassword']) {
            $repasswordErr = "<small><font color='red'>密码不一致</font></small>";
           }
            else {
                $data=input('post.');
                $index=new IndexModel();
                $res=$index->shezhi($res,$data);
                //var_dump($res);
                //var_dump($res=='2');die;
                if($res===2){
                    $this->error('旧密码错误!');
                }
                if($res!==false){
                    $this->success('修改密码成功!',url('index/index'));
                }
                else{
                    $this->error('修改密码失败!');
                }
                return;

            }

        }
        $this->assign('repasswordErr',$repasswordErr);
        $this->assign('vo',$res);
        return view();

    }







}
