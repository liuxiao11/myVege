<?php
namespace app\api\controller;

use think\Request;
use think\controller\Rest;
//use think\Db;
header('Access-Control-Allow-Origin:*');
class VegeCount extends Rest
{
    public function rest()
    {
        switch ($this->method){
            case 'get':     //查询
                $this->Count($id);
                break;
        }
    }
    /*蔬菜统计*/
    public function Count($id)
    {
        $join = [
            ['vegetable v','order.v_id = v.vege_id'],
        ];
        $id = Request::instance()->param();

        $d = date('d');
        $m = date('m');
        $y = date('Y');
        $select = group('order',$join,'v.vege_id,v.vege_name,v.vege_spec ,v.vege_unit ','v.vege_name',['a.user_adminid'=>$id['id']],'');
        $today_count = [];
        $month_count = [];
        $arr = [];
        if($select){
            foreach($select as $k =>$v){
                $tnums =  findone('order',[],'SUM(a.vege_num) as nums ',['a.user_adminid'=>$id['id'],'order_date'=>$d,'order_month'=>$m,'order_year'=>$y,'v_id'=>$v['vege_id']])['nums'];
                $price =  findone('order',[],'SUM(a.sum_price) as price ',['a.user_adminid'=>$id['id'],'order_date'=>$d,'order_month'=>$m,'order_year'=>$y,'v_id'=>$v['vege_id']])['price'];
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
                $mnums =  findone('order',[],'SUM(a.vege_num) as nums ',['a.user_adminid'=>$id['id'],'order_month'=>$m,'order_year'=>$y,'v_id'=>$v['vege_id']])['nums'];
                $mprice =  findone('order',[],'SUM(a.sum_price) as price ',['a.user_adminid'=>$id['id'],'order_month'=>$m,'order_year'=>$y,'v_id'=>$v['vege_id']])['price'];
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

}
