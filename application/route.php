<?php
use think\Route;

/*栗子*/
//Route::rule('a/','api/Login/a');

/*登录*/
Route::post('/login','api/Login/index'); //登录
//Route::get('/login/:name','api/Login/regiset');//注册
Route::put('/login/:id','api/Login/recove');//修改密码
Route::rule('/loginout/:id','api/Login/loginout');//退出
Route::rule('/checkLogin/:id','api/Login/checkLogin');//登陆检测


/*蔬菜增删改查*/
Route::post('/vege','api/Vegetables/VegeAdd');  //新增
Route::get('/vege/:id','api/Vegetables/VegeSelect'); //查询
Route::put('/vege/:id','api/Vegetables/VegeEdit'); //修改
Route::delete('/vege/:id','api/Vegetables/VegeDelete'); //删除

/*客户增删改查*/
Route::post('/user','api/User/UserAdd');  //新增
Route::get('/user/:id','api/User/UserSelect'); //查询
Route::put('/user/:id','api/User/UserEdit'); //修改
Route::delete('/user/:id','api/User/UserDelete'); //删除

/*订单增删改查*/
Route::post('/order','api/Order/OrderAdd');  //新增
Route::get('/order/:id','api/Order/OrderSelect'); //查询
Route::put('/order/:id','api/Order/OrderEdit'); //修改
Route::delete('/order/:id','api/Order/OrderDelete'); //删除
Route::rule('/vegename/:id','api/Order/VegeName');//所有蔬菜查询
Route::rule('/username/:id','api/Order/UserName');//所有商家查询
Route::get('/desc/:id','api/Order/Desc');//订单详情

/*蔬菜统计*/
Route::get('/vegecount/:id','api/VegeCount/Count'); //查询

/*商家统计*/
Route::get('/shopcount/:id','api/ShopCount/Count'); //查询

/*全部统计*/
Route::get('/allcount/:id','api/AllCount/Count'); //查询

/*系统设置*/
//Route::post('/system','api/System/Edit'); //新增
Route::get('/system/:id','api/System/Find');//查询
/*上传文件*/
Route::post('/system','api/System/UpFiles'); //上传

//导出文件

Route::get('/ExcelToday/:id','api/Order/ExcelToday'); //上传
Route::get('/ExcelMonth/:id','api/Order/ExcelMonth'); //上传