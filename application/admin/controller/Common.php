<?php

namespace app\admin\controller;
use think\Controller;
use think\facade\Session;
use think\captcha\Captcha;
use Db;
/**
 * 后台公共类
 */

class Common extends Controller 
{
    // 登录者id
    protected $admin_id;
    // 登录者角色id
    protected $role_id;
    // 登录者权限列表
    protected $privileges;
    
    public function __construct(\think\App $app = null) 
    {
        parent::__construct($app);
        if (Session::has('adminInfo.admin_id')) {
            $this->admin_id = Session::get('adminInfo.admin_id');
            $this->role_id = Session::get('adminInfo.role_id');
            $this->savePrivileges();
        }
    }
    
    /**
     * 赋予用户所属角色对应的权限
     */
    private function savePrivileges() {
        $privileges = [];
        // 如果是超级管理员，取得全部的权限资源
        if ($this->role_id == 1) {
            $privileges = Db::name('menu')->order('sort_order')->select();
        } else {
            $act_list = Db::name('role')->where('role_id', $this->role_id)->value('act_list');
 
            $privileges = Db::name('menu')->where("menu_id IN ($act_list)")->order('sort_order')->select();
        }
        
        Session::set('adminInfo.privileges', $privileges);
        $this->privileges = $privileges;
    }
    
    /**
     * 生成图形验证码
     * @param type $id
     * @return type
     */
    public function captcha($id = '') {
        $config = [
            'length'    => 4,
            'fontSize'  => 22,
            'useNoise'  => false
        ];
        $captcha  = new Captcha($config);
        return $captcha->entry($id);
    }
    
    /**
     * 统一给模型变量赋值
     * @param type $param
     */
    protected function assignParams($param = []) {
        foreach ($param as $k=>$v) {
            $this->assign($k, $v);
        }
    }
}
