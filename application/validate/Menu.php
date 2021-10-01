<?php

namespace app\validate;

use think\Validate;

class Menu extends Validate 
{
    protected $rule = [
        'menu_name'     => 'require',
        'ctl'           => 'requireCallback:checkRequire',
        'act'           => 'requireCallback:checkRequire',
    ];
    
    protected $message = [
        'menu_name.require'     => '菜单名称不能为空',
        'ctl.requireCallback'   => '当上级菜单不是顶级菜单时, 控制器不能为空',
        'act.requireCallback'   => '当上级菜单不是顶级菜单时, 方法不能为空',
    ];
    
    protected function checkRequire($val, $data) {
        return $data['pid'] != 0? true : false;
    }
}