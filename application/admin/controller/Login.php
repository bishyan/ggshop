<?php

namespace app\admin\controller;
use app\validate\Admin;
use think\facade\Request;
use Db;

class Login extends Common 
{
    public function login()
    {
        if (Request::isPost()) {
            $admin_name = Request::post('admin_name', '');
            $password = Request::post('password', '');
            $captcha_code = Request::post('captcha', '');
            
            if (!$admin_name) {
                $this->error('请输入账号!');
            }
            
            if (!$password) {
                $this->error('请输入密码!');
            }
            
            // 验证码
            if (!$captcha_code) {
                $this->error('请输入验证码!');
            } elseif (!check_captcha($captcha_code)) {
                $this->error('验证码错误!');
            }
            
            
            $adminInfo = Db::name('admin')->where('admin_name', $admin_name)->find();
            // 判断管理员是否存在
            if (!empty($adminInfo)) {
                // 是否启用
                if ($adminInfo['is_use'] == 1) {
                    // 判断密码
                    if ($adminInfo['password'] == encrypt($password)) {
                        session('adminInfo', $adminInfo);
                        $this->redirect('Index/index');
                    } else {
                        $this->error('账号或密码错误');
                    }                    
                } else {
                    $this->error('账号已禁用, 请联系管理员');
                }
            } else {
                $this->error('账号或密码错误');
            }
            exit();
        }
        
        return $this->fetch();
    }
    
    /**
     * 登出
     */
    public function logout()
    {
        session('adminInfo', null);
        $this->redirect('Login/login');
    }
}