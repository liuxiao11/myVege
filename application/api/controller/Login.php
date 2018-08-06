<?php
namespace app\api\controller;

use think\Session;
use think\Request;
use think\controller\Rest;

header('Access-Control-Allow-Origin:*');

class Login extends Rest
{
    public function rest()
    {
        switch ($this->method){
//            case 'get':     //查询
//                $this->regiset($name);
//                break;
            case 'post':    //登录和注册
                $this->index();
                break;
            case 'put':  //修改
                $this->recove($id);
                break;
        }
    }
    /*登录和注册*/
    public function index()
    {
        $data = Request::instance()->param();
        if(!empty($data['phone'])){
            $phone = checkData('Login',$data,'');
            if($phone){
                echo json_encode(['code'=>202,'msg'=>$phone]);
            }else {
                $data['password'] = md5($data['password']);
                $data['last_time'] = date('Y-m-d:H:i:s');
                $insert = addId('admin', $data);
                if ($insert) {
                    echo json(200, array("username" => $data['username'], "id" => $insert));
                } else {
                    echo json(202, '');
                }
            }
        }else{
            $find = findone('admin',[],'username,password,id',['username'=>$data['username'],'password'=>md5($data['password'])]);
            if($find){
                $date = date('Y-m-d:H:i:s');
                $token = $this->settoken();
                $edit = edit('admin',['id'=>$find['id']],['last_time'=>$date,'token'=>$token]);
                if($edit){
                    echo json(200,array("username"=>$find['username'],"id"=>$find['id']));
                }else{
                    echo json(202,'');
                }
            }else{
                echo json(202,'');
            }
        }
    }
    public static function settoken(){
        $str = md5(uniqid(md5(microtime(true)),true));  //生成一个不会重复的字符串
        $str = sha1($str);  //加密
        return $str;

    }

    /*忘记密码*/
    public function recove($id)
    {
        $data = json_decode(Request::instance()->param()['id'],true);
        $find = findone('admin',[],'username,password,id',['phone'=>$data['phone']]);
        if($find){
            $edit = edit('admin',['id'=>$find['id']],['password'=>md5($data['password'])]);
            if($edit){
                echo json(200,$find['username']);
            }else{
                echo json(202,'');
            }
        }else{
            echo json(404,'');
        }
    }
    /*登陆退出*/
    public function loginout($id){
        $find = findone('admin',[],'token',['id'=>$id]);
        if($find){
            $del = edit('admin',['id'=>$id],['token'=>""]);;
            if($del){
                echo json(200,"");
            }else{
                echo json(202,"");
            }
        }else{
            echo json(202,"");
        }

    }

    /*登陆状态监测*/
    public function checkLogin($id){
        $find = findone('admin',[],'token',['id'=>$id]);
        if($find['token'] == ""){
            echo json(202,"");
        }else{
            echo json(200,"");
        }
    }
}
