<?php
namespace app\api\controller;

use think\Request;
use think\controller\Rest;

header('Access-Control-Allow-Origin:*');
class Order extends Rest
{
    public function rest()
    {
        switch ($this->method){
            case 'get':     //查询
                $this->OrderSelect($id);
                break;
            case 'post':    //新增
                $this->OrderAdd();
                break;
            case 'put':  //修改
                $this->OrderEdit($id);
                break;
            case 'delete':  //修改
                $this->OrderDelete($id);
                break;
        }
    }
    /*所有蔬菜名称*/
    public function VegeName($id){
        $select = findMore('vegetable',[],' vege_name,vege_id,vege_price,vege_unit ',['user_adminid'=>$id],'','');
        if($select){
            echo json(200,$select);
        }else{
            echo json(202,'');
        }
    }
    /*所有商户名称*/
    public function UserName($id)
    {
        $select = findMore('user',[],'user_name,user_id ',['user_adminid'=>$id],'','');
        if($select){
            echo json(200,$select);
        }else{
            echo json(202,'');
        }
    }
    /*订单明细*/
    public function Desc($id){
        $id = json_decode(Request::instance()->param()['id'],true);
        $join = [
            ['vegetable v','order.v_id = v.vege_id'],
        ];
        $d = date('d');
        $m = date('m');
        $y = date('Y');
        $select = group('order',$join,'v.vege_id,v.vege_name,v.vege_spec ,v.vege_unit,a.vege_price ','v.vege_name',['a.user_adminid'=>$id['user_adminid'],'a.s_id'=>$id['s_id']],'a.order_time desc');
        $today_count = [];
        $month_count = [];
        $arr = [];
        if($select){
            foreach($select as $k =>$v){
                $tnums =  findone('order',[],'SUM(a.vege_num) as nums ',['a.user_adminid'=>$id['user_adminid'],'a.s_id'=>$id['s_id'],'order_date'=>$d,'order_month'=>$m,'order_year'=>$y,'v_id'=>$v['vege_id']])['nums'];
                $price =  findone('order',[],'SUM(a.sum_price) as price ',['a.user_adminid'=>$id['user_adminid'],'a.s_id'=>$id['s_id'],'order_date'=>$d,'order_month'=>$m,'order_year'=>$y,'v_id'=>$v['vege_id']])['price'];
                $today_count = [
                    'num'=>$tnums,
                    'price'=>$price
                ];
                if(empty($tnums) && empty($price)){
                    $today_count = [
                        'num'=>"",
                        'price'=>""
                    ];
                }
                $mnums =  findone('order',[],'SUM(a.vege_num) as nums ',['a.user_adminid'=>$id['user_adminid'],'a.s_id'=>$id['s_id'],'order_month'=>$m,'order_year'=>$y,'v_id'=>$v['vege_id']])['nums'];
                $mprice =  findone('order',[],'SUM(a.sum_price) as price ',['a.user_adminid'=>$id['user_adminid'],'a.s_id'=>$id['s_id'],'order_month'=>$m,'order_year'=>$y,'v_id'=>$v['vege_id']])['price'];
                $month_count = [
                    'num'=>$mnums,
                    'price'=>$mprice
                ];
                if(empty($mnums) && empty($mprice)){
                    $month_count = [
                        'num'=>"",
                        'price'=>""
                    ];
                }
                $v['today_count'] = $today_count;
                $v['month_count'] = $month_count;
                $arr[] = $v;
            }
        }
        if($arr){
            echo json(200,$arr);
        }else{
            echo json(202,'');
        }

    }

    /*订单查询*/
    public function OrderSelect($id)
    {
        $id = Request::instance()->param();
        $join = [
            ['vegetable v','order.v_id = v.vege_id'],
            ['user u','order.s_id = u.user_id'],
        ];
        $select = findMore('order',$join,'a.order_id, u.user_name,v.vege_name,a.vege_price,a.vege_num,a.sum_price,a.order_time',['a.user_adminid'=>$id['id']],'a.order_id desc');
        if($select){
            echo json(200,$select);
        }else{
            echo json(202,'');
        }
    }
    /*当天Excel导出*/
    public function ExcelToday($id){
        $id = json_decode(Request::instance()->param()['id'],true);
        $join = [
            ['vegetable v','order.v_id = v.vege_id'],
        ];
        $d = isset($id['date'])?$id['date']:date('d');
        $m = isset($id['month'])?$id['month']:date('m');
        $y = isset($id['year'])?$id['year']:date('Y');
        $today_select = group('order',$join,'v.vege_id, v.vege_name,v.vege_spec,v.vege_unit,a.vege_price,SUM(a.sum_price) as price ,SUM(a.vege_num) as nums ','v.vege_id',['a.user_adminid'=>$id['user_adminid'],'a.s_id'=>$id['s_id'],'order_date'=>$d,'order_month'=>$m,'order_year'=>$y],'a.order_time desc');
        $user_name = findone('user',[],'user_name',['user_id'=>$id['s_id']]);
        //蔬菜单位 0:斤  1：盒  2：瓶 3：箱  4：个  5：件
        if($today_select){
            foreach($today_select as $v){
                if($v['vege_unit'] == 0){
                    $v['vege_unit'] = "斤";
                }elseif($v['vege_unit'] == 1){
                    $v['vege_unit'] = "盒";
                }elseif($v['vege_unit'] == 2){
                    $v['vege_unit'] = "瓶";
                }elseif($v['vege_unit'] == 3){
                    $v['vege_unit'] = "箱";
                }elseif($v['vege_unit'] == 4){
                    $v['vege_unit'] = "个";
                }elseif($v['vege_unit'] == 5){
                    $v['vege_unit'] = "件";
                }
                $arr[] = $v;
            }
            $headArr = ['蔬菜id','蔬菜名称','蔬菜规格','蔬菜单位','蔬菜单价','总金额','总斤数'];
            excelExport($fileName=date("Y-m-d").'蔬菜统计导出',$headArr,$arr,$user_name['user_name']);
        }else{
            echo "<script>alert('暂无数据');window.history.go(-1); </script>" ;
        }
    }
    /*这个月Excel导出*/
    public function ExcelMonth($id){
        $id = json_decode(Request::instance()->param()['id'],true);
        $join = [
            ['vegetable v','order.v_id = v.vege_id'],
        ];
        $m = isset($id['month'])?$id['month']:date('m');
        $y = isset($id['year'])?$id['year']:date('Y');
        $month_select = group('order',$join,'v.vege_id, v.vege_name,v.vege_spec,v.vege_unit,a.vege_price ,SUM(a.sum_price) as price ,SUM(a.vege_num) as nums','v.vege_id',['a.user_adminid'=>$id['user_adminid'],'a.s_id'=>$id['s_id'],'order_month'=>$m,'order_year'=>$y],'a.order_time desc');
        $user_name = findone('user',[],'user_name',['user_id'=>$id['s_id']]);
        if($month_select){
            foreach($month_select as $v){
                if($v['vege_unit'] == 0){
                    $v['vege_unit'] = "斤";
                }elseif($v['vege_unit'] == 1){
                    $v['vege_unit'] = "盒";
                }elseif($v['vege_unit'] == 2){
                    $v['vege_unit'] = "瓶";
                }elseif($v['vege_unit'] == 3){
                    $v['vege_unit'] = "箱";
                }elseif($v['vege_unit'] == 4){
                    $v['vege_unit'] = "个";
                }elseif($v['vege_unit'] == 5){
                    $v['vege_unit'] = "件";
                }
                $arr[] = $v;
            }
            $headArr = ['蔬菜id','蔬菜名称','蔬菜规格','蔬菜单位','蔬菜单价','总金额','总斤数'];
            excelExport($fileName=date("Y-m-d").'蔬菜统计导出',$headArr,$arr,$user_name['user_name']);
        }else{
            echo "<script>alert('暂无数据');window.history.go(-1);</script>";
        }
    }
    /*订单增加*/
    public function OrderAdd()
    {
        $data = Request::instance()->param();
        $data['order_year'] = substr($data['order_time'],0,4);
        $data['order_month'] = substr($data['order_time'],5,2);
        $data['order_date'] = substr($data['order_time'],8,2);
        $data['order_insert_time'] = date('Y-m-d:H:i:s');
        $data['order_number'] = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        $insert = addData('order',$data);
        if($insert){
            echo json(200,'');
        }else{
            echo json(202,'');
        }
    }
    /*订单修改*/
    public function OrderEdit($id)
    {
        $data = json_decode(Request::instance()->param()['id'],true);
        $edit = edit('order',['order_id'=>$data['order_id']],$data);
        if($edit){
            echo json(200,'');
        }else{
            echo json(202,'');
        }
    }
    /*订单删除*/
    public function OrderDelete($id){
        $id = Request::instance()->param();
        $delete = del('order',['order_id'=>$id['id']]);
        if($delete){
            echo json(200,'');
        }else{
            echo json(202,'');
        }
    }

}
