<?php

namespace app\validate;
use think\Validate;

class Role extends Validate 
{
    protected $rule = [
        'role_name'     => 'require|unique:role,role_name',
        'menu_id'       => 'require'
    ];
    
    protected $message = [
        'role_name.require' => '角色名称不能为空',
        'role_name.unique'  => '角色已存在',
        'menu_id.require'   => '请为角色分配权限'
    ];
    
    public function sceneEdit() {
        return $this->only(['role_name'])->remove('role_name', 'unique');
    }
        
}