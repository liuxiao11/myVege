<?php
namespace app\api\validate;

use think\Validate;

class Login extends Validate
{
    protected $rule = [
        'phone' =>  'unique:admin',
    ];

     protected $message = [
		'phone.unique'  =>  '该手机号已注册',
    ];
}