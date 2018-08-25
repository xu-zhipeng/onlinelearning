<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\TikuModel;    //引入index模型模块

class Tiku extends Controller
{
    public function _initialize(){
        if(!session('id') || !session('name')){
            $this->error('您尚未登录系统',url('login/index'));
        }
    }

    public function xuanze()
    {
        $res=db('timu')->paginate(30,false,[
            'type'=>'AdminFenYe',
            'var_page'=>'page',
            ]);
        $this->assign('tmlist',$res);
        return view();
    }

    public function xuanzeadd(){
        if(request()->isPost())
        {
            $data=input('post.');
            $res=db('timu')->insert($data);
            if($res){
                $this->success('添加题目成功!',url('tiku/xuanzeadd'));
            }
            else{
                $this->error('添加题目失败!');
            }
            return;
        }
        $kemu=db('kemu')->select();
        $zhishidian=db('zhishidian')->select();
        $this->assign('kemu',$kemu);
        $this->assign('zhishidian',$zhishidian);
        return view();
    }

    public function panduan()
    {
        $tmlist=db('panduan')->paginate(30,false,[
            'type'=>'AdminFenYe',
            'var_page'=>'page',
            ]);
        $this->assign('tmlist',$tmlist);
        return view();
    }

    public function panduanadd(){
        if(request()->isPost())
        {
            $data=input('post.');
            $res=db('panduan')->insert($data);
            if($res){
                $this->success('添加题目成功!',url('tiku/xuanzeadd'));
            }
            else{
                $this->error('添加题目失败!');
            }
            return;
        }
        $kemu=db('kemu')->select();
        $zhishidian=db('zhishidian')->select();
        $this->assign('kemu',$kemu);
        $this->assign('zhishidian',$zhishidian);
        return view();
    }

    public function tiankong()
    {
        $tmlist=db('tiankong')->paginate(30,false,[
            'type'=>'AdminFenYe',
            'var_page'=>'page',
            ]);
        $this->assign('tmlist',$tmlist);
        return view();
    }

    public function tiankongadd(){
        if(request()->isPost())
        {
            $data=input('post.');
            $res=db('tiankong')->insert($data);
            if($res){
                $this->success('添加题目成功!',url('tiku/xuanzeadd'));
            }
            else{
                $this->error('添加题目失败!');
            }
            return;
        }
        $kemu=db('kemu')->select();
        $zhishidian=db('zhishidian')->select();
        $this->assign('kemu',$kemu);
        $this->assign('zhishidian',$zhishidian);
        return view();
    }

    public function jianda()
    {
        $tmlist=db('jianda')->paginate(30,false,[
            'type'=>'AdminFenYe',
            'var_page'=>'page',
            ]);
        $this->assign('tmlist',$tmlist);
        return view();
    }

    public function jiandaadd(){
        if(request()->isPost())
        {
            $data=input('post.');
            //保存图片到public/uploads
            if(request()->file('image')){
                $file = request()->file('image');
                //ROOT_PATH .
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info){
                // 成功上传后 获取上传信息
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $image=ROOT_PATH . 'public' . DS . 'uploads'.'/'.$info->getSaveName();
                $data['image']=$image;
                }else{
                    // 上传失败获取错误信息
                    echo $file->getError();
                }
            }
            $res=db('jianda')->insert($data);
            if($res){
                $this->success('添加题目成功!',url('tiku/xuanzeadd'));
            }
            else{
                $this->error('添加题目失败!');
            }
            return;
        }
        $kemu=db('kemu')->select();
        $zhishidian=db('zhishidian')->select();
        $this->assign('kemu',$kemu);
        $this->assign('zhishidian',$zhishidian);
        return view();
    }







}
