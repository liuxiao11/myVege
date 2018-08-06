<?php
namespace app\api\controller;

use think\Request;
use think\controller\Rest;
//use think\Db;
header('Access-Control-Allow-Origin:*');
class ShopCount extends Rest
{
    public function rest()
    {
        switch ($this->method){
            case 'get':     //查询
                $this->Count($id);
                break;
        }
    }
    /*商家统计*/
    public function Count($id)
    {
        $join = [
            ['user u','order.s_id = u.user_id'],
        ];
        $id = Request::instance()->param();
        $d = date('d');
        $m = date('m');
        $y = date('Y');
        $select = group('order',$join,'u.user_id, u.user_name,u.user_phone ,u.user_contacts,u.user_address','u.user_id',['a.user_adminid'=>$id['id']],'');
        $today_count = [];
        $month_count = [];
        $arr = [];
        if($select){
            foreach($select as $k =>$v){
                $tnums =  findone('order',[],'SUM(a.vege_num) as nums ',['a.user_adminid'=>$id['id'],'order_date'=>$d,'order_month'=>$m,'order_year'=>$y,'s_id'=>$v['user_id']])['nums'];
                $price =  findone('order',[],'SUM(a.sum_price) as price ',['a.user_adminid'=>$id['id'],'order_date'=>$d,'order_month'=>$m,'order_year'=>$y,'s_id'=>$v['user_id']])['price'];
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
                $mnums =  findone('order',[],'SUM(a.vege_num) as nums ',['a.user_adminid'=>$id['id'],'order_month'=>$m,'order_year'=>$y,'s_id'=>$v['user_id']])['nums'];
                $mprice =  findone('order',[],'SUM(a.sum_price) as price ',['a.user_adminid'=>$id['id'],'order_month'=>$m,'order_year'=>$y,'s_id'=>$v['user_id']])['price'];
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
