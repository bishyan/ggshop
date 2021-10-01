<?php
namespace app\admin\controller;
use think\facade\Request;
use app\admin\model\Role as RoleModel;
use app\validate\Role as RoleValidate;
use Db;

class Role extends Privilege 
{
    public function __construct() {
        parent::__construct();
        
        $this->assign('menuList', array_nested($this->privileges, 'menu_id'));
    }
    
    public function index() 
    {
        
        $this->assignParams([
            'title'    => '角色列表',
            'roleList' => Db::name('role')->select()
        ]);
        
        return $this->fetch('role_list');
    }
    
    /**
     * 添加角色
     * @return type
     */
    public function add()
    {
        if (Request::isPost()) {
            $this->requestLogic();
        }
        
        $this->assignParams([
            'title'    => '添加角色',
        ]);
        
        return $this->fetch('role_add');
    }
    
    /**
     * 编辑角色信息
     * @return type
     */
    public function edit() 
    {
        if (Request::isPost()) {
            $this->requestLogic();
        }
        
        $role_id = Request::param('role_id');
        $roleInfo = Db::name('role')->find($role_id);
        
        $this->assignParams([
            'title'     => '编辑角色',
            'roleInfo'  => $roleInfo,
            'act_list'  => explode(',', $roleInfo['act_list'])
        ]);
        return $this->fetch('role_edit');
    }
    
    
    /**
     * 添加或编辑角色业务逻辑
     */
    protected function requestLogic() 
    {
        $data = Request::post();

        // 判断当前是更新还是新增数据, true为更新, false为新增
        $type = isset($data['role_id']) && $data['role_id'] > 0? true : false;      
        
        // 验证数据
        $validate = new RoleValidate();
        if ($validate->check($data) !== true) {
            $this->error($validate->getError());
        }
        
        // 实例化模型, 调用方法添加或更新数据
        $model = new RoleModel();
        $data['act_list'] = implode(',', $data['menu_id']);
        
        $res = $model->exists($type)->data($data)->save();
        if ($res) {
            $this->success('操作成功', url('index'));
        } else {
            $this->error('操作失败');
        }
            
    }
    
    public function del() 
    {
        $role_id = Request::param('role_id', 0);
        $res = Db::name('role')->delete($role_id);
        
        if ($res !== false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
    
}