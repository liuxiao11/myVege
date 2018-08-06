<?php
namespace app\api\controller;

use think\Request;
use think\controller\Rest;

header('Access-Control-Allow-Origin:*');
class User extends Rest
{
    public function rest()
    {
        switch ($this->method){
            case 'get':     //查询
                $this->UserSelect($id);
                break;
            case 'post':    //新增
                $this->UserAdd();
                break;
            case 'put':  //修改
                $this->UserEdit($id);
                break;
            case 'delete':  //修改
                $this->UserDelete($id);
                break;
        }
    }
    /*商户查询*/
    public function UserSelect($id)
    {
        $id = Request::instance()->param();
        $select = findMore('user',[],'* ',['user_adminid'=>$id['id']],'','');
        if($select){
            echo json(200,$select);
        }else{
            echo json(202,'');
        }
    }
    /*商户增加*/
    public function UserAdd()
    {
        $data = Request::instance()->param();
        $data['user_time'] = date('Y-m-d:H:i:s');
        $insert = addData('user',$data);
        if($insert){
            echo json(200,$data['user_name']);
        }else{
            echo json(202,'');
        }
    }
    /*商户修改*/
    public function UserEdit($id)
    {
        $data = json_decode(Request::instance()->param()['id'],true);
        $edit = edit('user',['user_id'=>$data['user_id']],$data);
        if($edit){
            echo json(200,'');
        }else{
            echo json(202,'');
        }
    }
    /*商户删除*/
    public function UserDelete($id){
        $id = Request::instance()->param();
        $delete = del('user',['user_id'=>$id['id']]);
        if($delete){
            echo json(200,'');
        }else{
            echo json(202,'');
        }
    }

}
