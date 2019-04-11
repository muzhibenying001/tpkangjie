<?php


if( !function_exists('encrypt_password') ){
    #密码加密函数
    function encrypt_password( $password ){
        #加盐方式
        $salt = 'asdfjl.+-soi/*,*1o34ukl2q349012..,';

        return md5( md5($password) . md5($salt) );
    }
}