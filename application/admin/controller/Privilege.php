<?php

namespace app\admin\controller;
use think\facade\Request;
use think\facade\Session;
/**
 * 验证类, 后台需要验证权限的类统一继承此类
 */
class Privilege extends Common 
{
    public function __construct(\think\App $app = null) 
    {
        parent::__construct($app);
        
        if (!$this->admin_id) {
            $this->error('请先登陆', url('Login/login'));
        }
        
        $controller = Request::controller();
        $action = Request::action();
        // 后台首页和ajax请求只要登陆就可以访问, 不用验证权限
        if ($controller == 'Index' || substr($action, 0, 4) == 'ajax') {
            return;
        }
        
        // 判断是否具有权限操作当前动作
        if ( $this->role_id != 1 && ( empty($this->privileges) || !$this->hasPrivilege($controller, $action)) ) {
            echo "<script>alert('您没有权限操作此项, 如有需要请联系管理员'); history.back(-1);</script>";
            die();
        }
    }
    
    /**
     * 验证当前操作是否具有权限
     * @param type $controller
     * @param type $action
     * @param type $privilegeList
     * @return boolean
     */
    private function hasPrivilege($controller, $action, $privilegeList = null) 
    {
        if (is_null($privilegeList)) {
            $privilegeList = array_column($this->privileges, 'url');
        }
        if(!in_array($controller . '@'. $action, $privilegeList)) {
            return false;
        }
        
        return true;
    }
}