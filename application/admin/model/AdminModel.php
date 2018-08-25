<?php
namespace app\admin\model;
use think\Model;
use think\Controller;

class AdminModel extends Model
{

    public function zhishidiansort(){
        // $res=db('zhishidian')->paginate(30,false,[
        //     'type'=>'AdminFenYe',
        //     'var_page'=>'page',
        //     ]);
        // var_dump(is_array($res));
        // $re=array_splice($res[0],0,2);
        $res=db('zhishidian')->select();
        $arr=$res;
        return $this->sort($res);


    }

    // public function sort($data,$sjzsd=0,$jibie=0){
    //     static $arr=array();
    //     for ($i=0;$i<count($data);$i++) {
    //         if($data[$i]['sjzsd']==$sjzsd){
    //             $data[$i]['jibie']=$jibie;
    //             $arr[$i]=$data[$i];
    //             $this->sort($data,$data[$i]['zsdbh'],$jibie+1);
    //         }
    //     }
    //     return $arr;
    // }

    public function sort($data,$sjzsd=0,$jibie=0){
        static $arr=array();
        foreach ($data as $k => $v) {
            if($v['sjzsd']==$sjzsd){
                $v['jibie']=$jibie;
                $arr[]=$v;
                $this->sort($data,$v['zsdbh'],$jibie+1);
            }
        }
        return $arr;
    }
    public function zhishidianedit($data,$zsdbh){
        if($data['zsdlb']==0){
            $xj=db('zhishidian')->where('sjzsd',$zsdbh)->select();
            if(!empty($xj)){
                return false;
            }
        }
        return true;
    }
    public function getchildrenid($zsdbh){
        $data=db('zhishidian')->select();
        return $this->_getchildrenid($data,$zsdbh);
    }
    public function _getchildrenid($data,$zsdbh){
        static $a=array();
        foreach ($data as $k => $v) {
            if($v['sjzsd']==$zsdbh){
                $a[]=$v['zsdbh'];
                $this->_getchildrenid($data,$v['zsdbh']);
            }
        }
        return $a;
    }

    public function zhanghaoadd($data)
    {
        if(empty($data) || !is_array($data)){
            return false;
        }
        if($data['password']){
            $data['password']=md5($data['password']);
        }
        $res=db('yonghu')->insert($data);
        if($res!==false){
            return true;
        }
        else{
            return false;
        }
    }

    public function zhanghaolist()
    {
        return db('yonghu')->paginate(30,false,[
            'type'=>'AdminFenYe',
            'var_page'=>'page',
            ]);

    }

    public function zhanghaoedit($res,$data)
    {
        if(empty($data) || !is_array($data)){
            return false;
            }
            if($data['password']){
                $data['password']=md5($data['password']);
            }
            else{
                 $data['password']=$res['password'];
            }
            $res=db('yonghu')->where('id',$res['id'])->update($data);
            if($res!==false){
                return true;
            }
            else{
                return false;
            }

    }






}
