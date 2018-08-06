<?php
namespace app\api\controller;

use think\Request;
use think\controller\Rest;

header('Access-Control-Allow-Origin:*');
class Vegetables extends Rest
{
    public function rest()
    {
        switch ($this->method){
            case 'get':     //查询
                $this->VegeSelect($id);
                break;
            case 'post':    //新增
                $this->VegeAdd();
                break;
            case 'put':  //修改
                $this->VegeEdit($id);
                break;
            case 'delete':  //修改
                $this->VegeDelete($id);
                break;
        }
    }
    /*蔬菜查询*/
    public function VegeSelect($id)
    {
        $id = Request::instance()->param();
        $select = findMore('vegetable',[],'* ',['user_adminid'=>$id['id']],'','');
        if($select){
            echo json(200,$select);
        }else{
            echo json(202,'');
        }
    }
    /*蔬菜增加*/
    public function VegeAdd()
    {
        $data = Request::instance()->param();
        $data['vege_time'] = date('Y-m-d:H:i:s');
        $insert = addData('vegetable',$data);
        if($insert){
            echo json(200,$data['vege_name']);
        }else{
            echo json(202,'');
        }
    }
    /*蔬菜修改*/
    public function VegeEdit($id)
    {
        $data = json_decode(Request::instance()->param()['id'],true);
        $edit = edit('vegetable',['vege_id'=>$data['vege_id']],$data);
        if($edit){
            echo json(200,'');
        }else{
            echo json(202,'');
        }
    }
    /*蔬菜删除*/
    public function VegeDelete($id){
        $id = Request::instance()->param();
        $delete = del('vegetable',['vege_id'=>$id['id']]);
        if($delete){
            echo json(200,'');
        }else{
            echo json(202,'');
        }
    }

}
