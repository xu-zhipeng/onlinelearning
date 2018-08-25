<?php
namespace app\test\controller;
use think\Controller;
// use app\index\model\IndexModel;    //引入模型模块

class Index extends Controller
{
    public function index(){

        return view();
    }
     public function aja($id){
        if($id=="2"){
            echo "你好，这里是ajax";
        }
    }
}