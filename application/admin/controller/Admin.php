<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\AdminModel;    //引入模型模块

class Admin extends Controller
{
    public function _initialize(){
        if(!session('id') || !session('name')){
            $this->error('您尚未登录系统',url('login/index'));
        }
        $admin=db('yonghu')->where('name',session('name'))->find();
        if($admin['leibie']!="系统管理员"){
            $this->error('对不起，您的权限不够！');

        }
    }

    public function zhishidianadd(){
        if(request()->isPost())
        {
            $data=input('post.');
            $sj=db('zhishidian')->where('zsdbh',$data['sjzsd'])->find();
            if($sj['kmbh'] == $data['kmbh'] ||$data['sjzsd']=='0'){
                $res=db('zhishidian')->insert($data);
                if($res!=false){
                    $this->success('添加知识点成功!',url('admin/zhishidianadd'),'',1);
                }
                else{
                    $this->error('添加知识点失败!');
                }
            }else{
                $this->error('科目不匹配,添加知识点失败!');
            }
            return;
        }
        $kemu=db('kemu')->select();
        $index=new AdminModel();
        $zhishidian=$index->zhishidiansort();
        $this->assign([
            'kemu'=>$kemu,
            'zhishidian'=>$zhishidian]);
        return view();
    }

    public function zhishidianlist(){
        $index=new AdminModel();
        $res=$index->zhishidiansort();

        $this->assign('res',$res);
        return view();
    }

    public function zhishidianedit($zsdbh){
        $index=new AdminModel();
        if($zsdbh){
            $xianshi=db('zhishidian')->find($zsdbh);
            $this->assign('xianshi',$xianshi);
        }
        else{
            $this->error('非法进入!');
        }
        if(request()->isPost())
        {
            $data=input('post.');
            $sj=db('zhishidian')->where('zsdbh',$data['sjzsd'])->find();
            $xj=$index->zhishidianedit($data,$zsdbh);
            if($xj===false){
                $this->error('该知识点下有单页，修改失败！');
            }
            if($sj['kmbh'] == $data['kmbh'] ||$data['sjzsd']=='0'){
                $res=db('zhishidian')->where('zsdbh',$zsdbh)->update($data);
                if($res!=false){
                    $this->success('修改知识点成功!',url('admin/zhishidianlist'),'',1);
                }
                else{
                    $this->error('修改知识点失败!');
                }
            }else{
                $this->error('科目不匹配,修改知识点失败!');
            }
            return;
        }
        $kemu=db('kemu')->select();
        $zhishidian=$index->zhishidiansort();
        $this->assign([
            'kemu'=>$kemu,
            'zhishidian'=>$zhishidian]);
        return view();
    }

    public function zhishidiandelete($zsdbh){
        if($zsdbh){
            $index=new AdminModel();
            $children=$index->getchildrenid($zsdbh);
            $re=db('zhishidian')->delete($children);
            $res=db('zhishidian')->where('zsdbh',$zsdbh)->delete();
             if($res!=false && $re!==false){
                $this->success('删除知识点成功!',url('admin/zhishidianlist'),'',1);
            }
            else{
                $this->error('删除知识点失败!');
            }
        }
        else{
            $this->error('非法进入!');
        }
    }



    public function zhanghaoadd()
    {   //把账户管理与题库管理分开
        // if(session('leibie')!=='系统管理员'){
        //     $this->error('权限不足，无法访问！');
        // }
        if(request()->isPost()){
            $index=new AdminModel();
            $res=$index->zhanghaoadd(input('post.'));
            if($res){
                $this->success('添加管理员成功!',url('admin/zhanghaoadd'),'',1);
            }
            else{
                $this->error('添加管理员失败!');
            }

            return;
        }

        return view();

    }

    public function zhanghaolist()
    {
        $index=new AdminModel();
        $res=$index->zhanghaolist();
        $this->assign('res',$res);
        return view();
    }

    public function zhanghaoedit($id)
    {
        if($id){
            $res=db('yonghu')->find($id);
            $this->assign('vo',$res);
        }
        else{
            $this->error('非法进入!');
        }
        if(request()->isPost()){
            $data=input('post.');
            $index=new AdminModel();
            $res=$index->zhanghaoedit($res,$data);
            if($res!=false){
                $this->success('编辑管理员成功!',url('admin/zhanghaolist'),'',1);
            }
            else{
                $this->error('编辑管理员失败!');
            }

            return;
        }
        return view();
    }

    public function zhanghaodelete($id){
        if($id){
            $res=db('yonghu')->where('id',$id)->delete();
             if($res!=false){
                $this->success('删除管理员成功!',url('admin/zhanghaolist'),'',1);
            }
            else{
                $this->error('删除管理员失败!');
            }
        }
        else{
            $this->error('非法进入!');
        }

    }


}
