<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Session;
use think\Cookie;
use app\index\model\IndexModel;   //引入模型模块

class Index extends Controller
{
    public function index(){
        $data=db("kemu")->select();
        $data1=db("zhishidian")->where('sjzsd',0)->select();
        $data2=db("zhishidian")->where("sjzsd>=1")->select();
        $this->assign('kmlist',$data);
        $this->assign('zsdlist',$data1);
        $this->assign('zsdlist1',$data2);
        return view();
    }

    public function login(){

        if(request()->isPost()){
            $data=input('post.');
            $index=new indexModel();
            $res=$index->login($data);
            if($res==1){
                $this->error('用户名不存在!');
            }
            if($res==2){
                $this->error('密码错误!');
            }
            if($res==3){
                $this->success('登录成功!',url('index/index'),'',1);
            }

            return;
        }
        return view();
    }

    public function register(){

        $repasswordErr = "<small></small>";
        if(request()->isPost()){
            if ($_POST['password']!=$_POST['passwordRepeat']) {
            $repasswordErr = "<small><font color='red' align='center'>密码不一致</font></small>";
           }
           else{
                $data=input('post.');
                //var_dump($data);
                $da=array_splice($data,2,1);
                if($data['password']){
                    $data['password']=md5($data['password']);
                }
               // var_dump($data);die;
                $res=db('user')->insert($data);
                if($res){
                    $this->success('注册成功!',url('index/index'),'',1);
                }
                else{
                    $this->error('注册失败!');
                }
                return;
            }
        }
        $this->assign('repasswordErr',$repasswordErr);
        return view();
    }
    public function logout(){
        session(null);
        $this->success('退出系统成功！',url('index/index'),'',1);
    }
    public function worklist($zsdbh,$kmbh)
    {
        /*
        *将知识点编号的题目从数据库提取
        *分页管理
        */
        $subjects=db("timu")->where("zsdbh",$zsdbh)->paginate(10,false,[
            'type'=>'workonlinePaginate',
            'var_page'=>'page',
            ]);
        /*
        *将科目相关的知识点从数据库提取
        */
        $knowledgepoint=db("zhishidian")->where("kmbh",$kmbh)->select();
        /*
        *将科目列表从数据库提取
        */
        $course=db("kemu")->select();
        /*
        *将题型列表从数据库提取
        */
        $types=db("types")->select();

        /*
        *传送数据
        */
        $this->assign("subjects",$subjects);
        $this->assign("knowledgepoint",$knowledgepoint);
        $this->assign("kmbh",$kmbh);
        $this->assign("zsdbh",$zsdbh);
        $this->assign("course",$course);
        $this->assign("types",$types);
        return view();
    }

    public function workonline($zsdbh,$kmbh)
    {
        //将知识点编号的题目从数据库提取分页管理
        $datacount=db("timu")->count();
        $i=1;
        $ary=array();
        if($datacount>=20)
        {
            for($i=0;$i<40;$i++)
            {
                $randnum=mt_rand(1,$datacount);
                if(!in_array($randnum, $ary)){
                    $ary[]=$randnum;
                }
                else{
                    $i--;
                }
                $i++;
            }
        }
        $subjects=db("timu")->where('tmbh','in',$ary,'or')->select();
        //将科目相关的知识点从数据库提取
        $knowledgepoint=db("zhishidian")->where("kmbh",$kmbh)->select();
        //将科目列表从数据库提取
        $course=db("kemu")->select();
        //将题型列表从数据库提取
        $types=db("types")->select();
        //传送数据
        $this->assign("subjects",$subjects);
        $this->assign("knowledgepoint",$knowledgepoint);
        $this->assign("kmbh",$kmbh);
        $this->assign("zsdbh",$zsdbh);
        $this->assign("course",$course);
        $this->assign("types",$types);

        return view();

    }
    public function checkanswer(){
        if(request()->isPost()){
            $data=input('post.');
            //获取科目编号，考点检索需要
            $kmbh=$data['kmbh'];
            //var_dump($kmbh);
            $ary=array();
            foreach ($data as $k => $v) {
                # code...
                $ary[]=$k;
            }
            $subjects=db("timu")->where('tmbh','in',$ary,'or')->select();
            //print_r($data);
            //print_r($subjects);

            foreach ($subjects as $k => $v) {
                # code...
                //去除空格再转大写
                $v['da']=strtoupper(trim($v['da']));
                //赋值把答案转为大写
                $subjects[$k]['da']=$v['da'];
                //var_dump($v['da']);
                if(strcasecmp($v['da'],$data[$v['tmbh']])==0){
                    //将对错赋给subjects数组
                    $subjects[$k]+=array('correct'=>'true');
                }else{
                    //将对错赋给subjects数组
                    $subjects[$k]+=array('correct'=>'false');
                }
                //将选的值赋给subjects数组
                $subjects[$k]+=array('select'=>$data[$v['tmbh']]);
            }
            //print_r($subjects);
        //将科目相关的知识点从数据库提取
        $knowledgepoint=db("zhishidian")->where("kmbh",$kmbh)->select();
        //将科目列表从数据库提取
        $course=db("kemu")->select();
        //将题型列表从数据库提取
        $types=db("types")->select();
        //传送数据
        $this->assign("subjects",$subjects);
        $this->assign("knowledgepoint",$knowledgepoint);
        $this->assign("kmbh",$kmbh);
        //$this->assign("zsdbh",$zsdbh);
        $this->assign("course",$course);
        $this->assign("types",$types);
        return view();
        }
        return ;
    }
    public function qessearch(){
        if(request()->isPost()){
            $search=input('index_none_header_sysc');
            session('search', $search);
            $searchlist=db("timu")->where("tg","like","{$search}%")->paginate(10,false,[
            "type"=>"QesSearchPaginate",
            "var_type"=>"page",
            ]);
            $this->assign("searchlist",$searchlist);
            return view();
        }elseif(Session::has('search')){
            $search=session('search');
            $searchlist=db("timu")->where("tg","like","{$search}%")->paginate(10,false,[
            "type"=>"QesSearchPaginate",
            "var_type"=>"page",
            ]);
            $this->assign("searchlist",$searchlist);
            return view();
        }else{
            $this->redirect('index/index');
        }

    }
    public function test(){
        if(request()->isPost()){
            $search=input('index_none_header_sysc');
            session('search', $search);
            $page=1;
        }else{
            $page=input('page');
        }
        $search=session('search');
        $count=db("timu")->where("tg","like","{$search}%")->count();
        $pages=$count%10==0?$count/10:(int)($count/10)+1;
        $searchlist=db("timu")->where("tg","like","{$search}%")->page($page,10)->select();
        $record=$page==1?1:($page-1)*10+1;
        $this->assign("searchlist",$searchlist);
        $this->assign("page",$page);
        $this->assign("pages",$pages);
        $this->assign("halfpages",(int)$pages/2);
        //return view();

    }



}