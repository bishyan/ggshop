<?php

namespace app\admin\controller;
use Db;
use think\facade\Request;
use app\validate\Admin as AdminValidate;
use app\admin\model\Admin as AdminModel;

/**
 * 权限资源控制器
 */

class Admin extends Privilege
{
    public function __construct() 
    {
        parent::__construct();

        $this->assign('roleList',  Db::name('role')->select());
    }
    
    /**
     * 展示管理员列表
     * @return type
     */
    public function index()
    {
        $adminList = Db::name('admin')
                ->alias('a')
                ->join('role', 'role.role_id =a.role_id', 'LEFT')
                ->field('a.*, role.role_name')
                ->select();
        
        $this->assignParams([
            'title'     => '管理员列表',
            'adminList' => $adminList,
        ]);

        return $this->fetch('admin_list');
    }
    
    /**
     * 添加管理员
     * @return type
     */
    public function add() 
    {
        if (Request::isPost()) {
            $this->requestLogic();
        }
        
        $this->assignParams([
            'title'     => '添加管理员'
        ]);
        
        return $this->fetch('admin_add');
    }
    
    
    /**
     * 编辑管理员
     * @return type
     */
    public function edit() 
    {
        if (Request::isPost()) {
            $this->requestLogic();
        }
        
        $admin_id = Request::param('admin_id', 0);

        $this->assignParams([
            'title'     => '编辑管理员',
            'adminInfo'  => Db::name('admin')->find($admin_id),
        ]);

        return $this->fetch('admin_edit');
    }
    
    /**
     * 新增或编辑管理员信息业务逻辑
     */
    protected function requestLogic() 
    {
        if (Request::isPost()) {
            $data = Request::post();
            // 判断当前是更新还是新增数据, true为更新, false为新增
            $type = isset($data['admin_id']) && $data['admin_id'] > 0 ? true : false;
            
            // 验证数据
            $validate = new AdminValidate();
            $scene = $type? 'edit': '';  // 验证场景
            $res = $validate->scene($scene)->check($data);
            if (!$res) {
                $this->error($validate->getError());
            } 
            
            
            $model = new AdminModel();
            if ($type) {
                unset($data['admin_name']);
                if ($data['password'] == '') {
                    unset($data['password']);
                }
            } else {
                $data['password'] = encrypt($data['password']);
            }
            
            $info = $model->exists($type)->allowField(true)->data($data)->save();
            if ($info) {
                $this->success('操作成功', url('index'));
            } else {
                $this->error('操作失败');
            }
        } else {
            $this->error('非法请求');
        }
    }
    
    public function del() {
        $admin_id = Request::param('admin_id', 0);
 
 
        $res = Db::name('admin')->delete($admin_id);
        if ($res) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
    
}