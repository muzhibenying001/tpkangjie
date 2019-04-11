<?php

namespace app\Admin\controller;

use think\Controller;
use think\Request;

use app\admin\model\Admin as admin;

class Login extends Controller
{
    /**
     * 显示登陆页面
     *
     * @return \think\Response
     */
    public function index()
    {
        return view('login');
    }

    public function login()
    {
        # 获取表单数据
        $data = request() -> param();
        # 设定验证规则
        $rule = [
            'username'  => 'require',
            'password'  => 'require|length:4,16',
            'captcha'      => 'require|length:4'
        ];
        $msg = [
            'username.require'  => '用户名不能为空',
            'password.require'  => '密码不能为空',
            'password.length'   => '密码长度在4-16之间',
            'captcha.require'      => '验证码不能为空',
            'captcha.length'       => '验证码长度必须是4位'
        ];
        $validate = new \think\Validate($rule,$msg);
        # 判断验证结果
        if( ! $validate -> check( $data ) ){
            # 获取错误信息
            $error = $validate -> getError();
            $res = [
                'code' => 300,
                'msg'  => $error
            ];

            return json($res);
        }
        #检测验证码
        if( !captcha_check($data['captcha']) ){

            $res = [
                'code' => 300,
                'msg'  => '验证码错误'
            ];

            return json($res);
        }

        # 查询数据库
        $manager = admin::where('username',$data['username']) -> where('password',encrypt_password( $data['password']) ) -> find();

        if( $manager ){
            #存入session
            session('manager_info',$manager->toArray());
            #记录登陆时间
            $manager->logintime = time();
            #记录入库
            $manager->save();

            $res = [
                'code' => 200,
                'msg'  => '登陆成功'
            ];
        }else{
            $res = [
                'code' => 400,
                'msg'  => '用户名或密码有误'
            ];
        }
        return json($res);


    }

    #退出系统
    public function logout(){
        #清空session
        session(null);
        #跳转到登录页
        $this -> redirect('admin/login/index');
    }

    # 生成密码
    public function test(){
        echo encrypt_password('123456');
    }
}
