<?php
namespace app\api\controller;

use think\Request;
use think\controller\Rest;
//use think\Db;
header('Access-Control-Allow-Origin:*');
class AllCount extends Rest
{
    public function rest()
    {
        switch ($this->method){
            case 'get':     //查询
                $this->Count($id);
                break;
        }
    }
    /*全部统计*/
    public function Count($id)
    {
        //$type = 1 类型蔬菜;  $year = 年（为空则是今年）; $month = 月（为空则为当月）;
        $data = json_decode(Request::instance()->param()['id'],true);
        $type = $data['type'];
        $id = $data['id'];//当前用户的id
        $today_count = [];
        $arr = [];
        if($type == 1){
            $join = [
                ['vegetable v','order.v_id = v.vege_id'],
            ];
            if($data['month'] == "" && !empty($data['year'])){//只选年 当年全部
                $y = $data['year'];
                $select = group('order',$join,'v.vege_id,v.vege_name,v.vege_spec ,v.vege_unit','v.vege_name',['a.user_adminid'=>$id],'');
                if($select){
                    foreach($select as $k =>$v){
                        $tnums =  findone('order',[],'SUM(a.vege_num) as nums ',['a.user_adminid'=>$id,'order_year'=>$y,'v_id'=>$v['vege_id']])['nums'];
                        $price =  findone('order',[],'SUM(a.sum_price) as price ',['a.user_adminid'=>$id,'order_year'=>$y,'v_id'=>$v['vege_id']])['price'];
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
                        $v['all_count'] = $today_count;
                        $arr[] = $v;
                    }
                }
            }elseif($data['year'] == "" && !empty($data['month'])){//只选月 默认今年
                $y = date('Y');
                $m = $data['month'];
                $select = group('order',$join,'v.vege_id,v.vege_name,v.vege_spec ,v.vege_unit','v.vege_name',['a.user_adminid'=>$id],'');
                if($select){
                    foreach($select as $k =>$v){
                        $tnums =  findone('order',[],'SUM(a.vege_num) as nums ',['a.user_adminid'=>$id,'order_month'=>$m,'order_year'=>$y,'v_id'=>$v['vege_id']])['nums'];
                        $price =  findone('order',[],'SUM(a.sum_price) as price ',['a.user_adminid'=>$id,'order_month'=>$m,'order_year'=>$y,'v_id'=>$v['vege_id']])['price'];
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
                        $v['all_count'] = $today_count;
                        $arr[] = $v;
                    }
                }
            }elseif($data['month'] == "" && $data['year'] == ""){//年月都不选 默认全部
                $select = group('order',$join,'v.vege_id,v.vege_name,v.vege_spec ,v.vege_unit','v.vege_name',['a.user_adminid'=>$id],'');
                if($select){
                    foreach($select as $k =>$v){
                        $tnums =  findone('order',[],'SUM(a.vege_num) as nums ',['a.user_adminid'=>$id,'v_id'=>$v['vege_id']])['nums'];
                        $price =  findone('order',[],'SUM(a.sum_price) as price ',['a.user_adminid'=>$id,'v_id'=>$v['vege_id']])['price'];
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
                        $v['all_count'] = $today_count;
                        $arr[] = $v;
                    }
                }
            }elseif(!empty($data['month']) && !empty($data['year'])){//都选
                $y = $data['year'];
                $m = $data['month'];
                $select = group('order',$join,'v.vege_id,v.vege_name,v.vege_spec ,v.vege_unit','v.vege_name',['a.user_adminid'=>$id],'');
                if($select){
                    foreach($select as $k =>$v){
                        $tnums =  findone('order',[],'SUM(a.vege_num) as nums ',['a.user_adminid'=>$id,'order_month'=>$m,'order_year'=>$y,'v_id'=>$v['vege_id']])['nums'];
                        $price =  findone('order',[],'SUM(a.sum_price) as price ',['a.user_adminid'=>$id,'order_month'=>$m,'order_year'=>$y,'v_id'=>$v['vege_id']])['price'];
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
                        $v['all_count'] = $today_count;
                        $arr[] = $v;
                    }
                }
            }
        }elseif($type == 0){
            $join = [
                ['user u','order.s_id = u.user_id'],
            ];
            if($data['month'] == "" && !empty($data['year'])){//只选年 当年全部
                $y = $data['year'];
                $select = group('order',$join,'u.user_id, u.user_name,u.user_phone ,u.user_contacts,u.user_address','u.user_id',['a.user_adminid'=>$id],'');
                if($select){
                    foreach($select as $k =>$v){
                        $tnums =  findone('order',[],'SUM(a.vege_num) as nums ',['a.user_adminid'=>$id,'order_year'=>$y,'s_id'=>$v['user_id']])['nums'];
                        $price =  findone('order',[],'SUM(a.sum_price) as price ',['a.user_adminid'=>$id,'order_year'=>$y,'s_id'=>$v['user_id']])['price'];
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
                        $v['all_count'] = $today_count;
                        $arr[] = $v;
                    }
                }
            }elseif($data['year'] == "" && !empty($data['month'])){//只选月 默认今年
                $y = date('Y');
                $m = $data['month'];
                $select = group('order',$join,'u.user_id, u.user_name,u.user_phone ,u.user_contacts,u.user_address','u.user_id',['a.user_adminid'=>$id],'');
                if($select){
                    foreach($select as $k =>$v){
                        $tnums =  findone('order',[],'SUM(a.vege_num) as nums ',['a.user_adminid'=>$id,'order_month'=>$m,'order_year'=>$y,'s_id'=>$v['user_id']])['nums'];
                        $price =  findone('order',[],'SUM(a.sum_price) as price ',['a.user_adminid'=>$id,'order_month'=>$m,'order_year'=>$y,'s_id'=>$v['user_id']])['price'];
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
                        $v['all_count'] = $today_count;
                        $arr[] = $v;
                    }
                }
            }elseif($data['month'] == "" && $data['year'] == ""){//年月都不选 默认全部
                $select = group('order',$join,'u.user_id, u.user_name,u.user_phone ,u.user_contacts,u.user_address','u.user_id',['a.user_adminid'=>$id],'');
                if($select){
                    foreach($select as $k =>$v){
                        $tnums =  findone('order',[],'SUM(a.vege_num) as nums ',['a.user_adminid'=>$id,'s_id'=>$v['user_id']])['nums'];
                        $price =  findone('order',[],'SUM(a.sum_price) as price ',['a.user_adminid'=>$id,'s_id'=>$v['user_id']])['price'];
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
                        $v['all_count'] = $today_count;
                        $arr[] = $v;
                    }
                }
            }elseif(!empty($data['month']) && !empty($data['year'])){//都选
                $y = $data['year'];
                $m = $data['month'];
                $select = group('order',$join,'u.user_id, u.user_name,u.user_phone ,u.user_contacts,u.user_address','u.user_id',['a.user_adminid'=>$id],'');
                if($select){
                    foreach($select as $k =>$v){
                        $tnums =  findone('order',[],'SUM(a.vege_num) as nums ',['a.user_adminid'=>$id,'order_month'=>$m,'order_year'=>$y,'s_id'=>$v['user_id']])['nums'];
                        $price =  findone('order',[],'SUM(a.sum_price) as price ',['a.user_adminid'=>$id,'order_month'=>$m,'order_year'=>$y,'s_id'=>$v['user_id']])['price'];
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
                        $v['all_count'] = $today_count;
                        $arr[] = $v;
                    }
                }
            }
        }
        if($arr){
            echo json(200,$arr);
        }else{
            echo json(202,'');
        }
    }


}
