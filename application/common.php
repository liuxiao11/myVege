<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\Db;
use think\Loader;
use think\Cache;
use think\Request;

function isMobile()
{ 
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
    {
        return true;
    } 
    if (isset ($_SERVER['HTTP_VIA']))
    { 
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    } 
    if (isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array ('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
            ); 
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
        {
            return true;
        } 
    } 
    if (isset ($_SERVER['HTTP_ACCEPT']))
    { 
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        } 
    } 
    return false;
}

//导出数据到excel
function excelExport($fileName='',$headArr=[],$data=[],$user_name) {
    $fileName .= "_" . date("Y_m_d", Request::instance()->time()) . ".xls";
    $objPHPExcel = new \PHPExcel();
    $objPHPExcel->getProperties();
	$objPHPExcel->getActiveSheet()->mergeCells('A1:G1');//合并单元格
	$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');//合并单元格
	$objPHPExcel->getActiveSheet()->mergeCells('A3:C3');//合并单元格
	$objPHPExcel->getActiveSheet()->mergeCells('D3:G3');//合并单元格
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('宋体')->setSize(11)->setBold(true); //字体
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setName('宋体')->setSize(10)->setBold(true); //字体
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//居中
	$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//居中
	$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

    $key = ord("A"); // 设置表头
    foreach ($headArr as $v) {
        $colum = chr($key);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '4', $v);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '4', $v);
        $key += 1;
    }
    $column = 5;
    $objActSheet = $objPHPExcel->getActiveSheet();
    foreach ($data as $key => $rows) { // 行写入
		$objActSheet->setCellValue("A1","陕西睿城农副产品有限公司");
		$objActSheet->setCellValue("A2","配送结算单");
		$objActSheet->setCellValue("A3","收货单位:".$user_name);
		$objActSheet->setCellValue("D3","时间:".date('Y-m-d'));
        $span = ord("A");
        foreach ($rows as $keyName => $value) { // 列写入
            $objActSheet->setCellValue(chr($span) . $column, $value);
            $span++;
        }
        $column++;
    }
    $fileName = iconv("utf-8", "gb2312", $fileName); // 重命名表
    $objPHPExcel->setActiveSheetIndex(0); // 设置活动单指数到第一个表,所以Excel打开这是第一个表
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename='$fileName'");
    header('Cache-Control: max-age=0');
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output'); // 文件通过浏览器下载
    exit();
}

//查询条数
function dataCount($table,$where)
{
	$data = Db::name($table)->where($where)->count();
	if($data){
		return $data;
	}else{
		return 0;
	}
}

//单条查询
function findone($table,$join,$field,$where)
{
	$data = Db::name($table)->alias('a')->join($join)->field($field)->where($where)->find();
	if($data){
		return $data;
	}else{
		return false;
	}
}
 
//多条查询
function findMore($table,$join,$field,$where,$order,$num='')
{
	$data = Db::name($table)->alias('a')->join($join)->field($field)->where($where)->order($order)->limit($num)->select();
	if($data){
		return $data;
	}else{
		return '';
	}
}

//分页
function findMorePg($table,$join,$field,$group,$where,$order,$num)
{
	$data = Db::name($table)->alias('a')->join($join)->field($field)->group($group)->where($where)->order($order)->paginate($num);
	if($data->items()!=array()){
		return $data;
	}else{
		return false;
	}
}

//添加数据
function addData($table,$data)
{
	$data = Db::name($table)->insert($data);
	if($data){
		return $data;
	}else{
		return msg('添加失败！');
	}
}

//递归处理
function getTree($data, $pId)
{
	$tree = array();
	foreach($data as $k => $v)
	{
		if($v['pid'] == $pId)
		{
		$v['cnav'] = getTree($data, $v['id']);
		$tree[] = $v;
		}
	}
		return $tree;
}

//弹出提示信息，返回操作之前
function msg($msg)
{
	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
	echo "<script>alert('$msg');window.location.href=document.referrer; </script>";
}

//弹出提示信息,返回正在操作的时候
function msgback($msg)
{
	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
	echo "<script>alert('$msg');window.location.href=history.go(-1);  </script>";
}

//添加数据返回id
function addId($table,$data)
{
	$data = Db::name($table)->insertGetId($data);
	if($data){
		return $data;
	}else{
		return false;
	}
}



//删除数据
function del($table,$where)
{
	$data = Db::name($table)->where($where)->delete();
	if($data){
		return $data;
	}else{
		return false;
	}
}


//数据修改
function edit($table,$where,$data)
{
	$data = Db::name($table)->where($where)->update($data);
	if($data){
		return $data;
	}else{
		msg('数据修改失败！');
	}
}

//设置缓存
function setCache($type,$name,$value,$time=0)
{
	return Cache::store($type)->set($name,$value,$time);
}

//获取缓存
function getCache($type,$name)
{
	return Cache::store($type)->get($name);
}

//分组查询
function group($table,$join,$field,$group,$where,$order)
{
	$data = Db::name($table)->alias('a')->join($join)->field($field)->group($group)->where($where)->order($order)->select();
	if($data){
		return $data;
	}else{
		return '';
	}
}

//调用验证
function checkData($val,$data,$scene)
{
	$validate = Loader::validate($val);
	if(!$validate->scene($scene)->check($data)){
		return $validate->getError();
	}else{
		return false;
	}
}

//聚合求和
function sumAll($table,$where,$zd)
{
	$data = Db::name($table)->where($where)->sum($zd);
	if($data){
		return $data;
	}else{
		return 0;
	}
}

function json($code,$data)
{
	$data =['code'=>$code,'msg'=>jsonMsg($code),'res'=>$data];
	return json_encode($data);
}

function jsonMsg($code)
{
	switch($code){
		case 200:
			return '请求成功';
		break;
		case 202:
			return '请求失败';
			break;
		case 404:
			return '资源未找到';
			break;
	}
}
//上传文件
function upFile($fileName)
{
	$file = request()->file($fileName);
	if($file){
	    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
       	    if($info){
               	 $src = $info->getSaveName();
               	 $src = str_replace("\\","/",$src);
        	        return '/uploads/'.$src;
      	    }else{
             	   msgback($file->getError());
        	   die;
	     }
	}else{
	    return false;
	}
}
