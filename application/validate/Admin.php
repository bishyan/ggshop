<?php

namespace app\validate;

use think\Validate;

/**
 * 管理员验证器
 */
class Admin extends Validate 
{
    protected $rule = [
        'admin_name'     => 'require|alphaDash|unique:admin,admin_name',
        'password'       => 'require|length:6,10|alphaDash',
        'repassword'     => 'requireWith:password|confirm:password',
        'email'          => 'require|email|unique:admin,email',
    ];
    
    protected $message = [
        'admin_name.require'     => '管理员账号不能为空',
        'admin_name.alphaDash'   => '管理员账号只能由字母,数字,下划线,破折号组成',
        'admin_name.unique'      => '管理员账号已存在',
        'password.require'       => '请输入密码',
        'password.length'        => '密码位数最少6位,最多10位',
        'password.alphaDash'     => '密码只能由字母,数字,下划线,破折号组成',
        'repassword.requireWith' => '请输入确认密码',
        'repassword.confirm'     => '两次输入的密码不一致',
        'email.require'          => 'email不能为空',
        'email.email'            => 'email格式不符',
        'email.unique'           => '当前email已注册, 请更换',
    ];
    
    protected function sceneEdit() {
        $this->remove('password', 'require');
    }
}