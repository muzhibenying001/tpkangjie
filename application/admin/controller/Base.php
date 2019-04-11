<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Base extends Controller
{
   public function __construct( Request $request ){
   		# 防止父类构造方法被重写
        parent::__construct($request);
        # 判断是否登陆
        if(!session('manager_info'))
        {
        	# 如果没有登陆强制跳转到登陆页
        	$this -> redirect('Login/index');
        }

   }
   
}
