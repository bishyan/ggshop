<?php

namespace app\admin\controller;
use Db;
use think\facade\Request;
use app\validate\Menu as MenuValidate;
use app\admin\model\Menu as MenuModel;

/**
 * 权限资源控制器
 */

class Menu extends Privilege
{
    public function __construct() 
    {
        parent::__construct();

        $this->assign('menuList',  array_tree($this->privileges, 'menu_id'));
    }
    
    /**
     * 展示菜单列表
     * @return type
     */
    public function index()
    {
        $this->assignParams([
            'title'     => '菜单列表'
        ]);

        return $this->fetch('menu_list');
    }
    
    /**
     * 添加权限
     * @return type
     */
    public function add() 
    {
        if (Request::isPost()) {
            $this->requestLogic();
        }
        
        $this->assignParams([
            'title'     => '添加菜单'
        ]);
        return $this->fetch('menu_add');
    }
    
    
    /**
     * 编辑菜单
     * @return type
     */
    public function edit() 
    {
        if (Request::isPost()) {
            $this->requestLogic();
        }
        
        $menu_id = Request::param('menu_id/d', 0);

        $this->assignParams([
            'title'     => '添加菜单',
            'menuInfo'  => Db::name('menu')->find($menu_id),
            'subMenuIds'=> getSubIds($this->privileges, 'menu_id', $menu_id),
        ]);

        return $this->fetch('menu_edit');
    }
    
    
    protected function requestLogic() 
    {
        if (Request::isPost()) {
            $data = Request::post();
            // 验证数据
            $validate = new MenuValidate();
            $res = $validate->check($data);
            if (!$res) {
                $this->error($validate->getError());
            } else {
                $data['url'] = $data['pid'] == 0 ? '' : $data['ctl'] . '@' . $data['act'];
            }
            
            $model = new MenuModel();
            $type = isset($data['menu_id'])? true : false;
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
        $menu_id = Request::param('menu_id', 0);
        $menuList = Db::name('menu')->select();
        
        $subIds = getSubIds($menuList, 'menu_id', $menu_id);
        if (!empty($subIds)) {
            $this->error('当前菜单下有子菜单, 不允许删除');
        }
        
        $res = Db::name('menu')->delete($menu_id);
        if ($res) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
    
    public function ajaxGetSubIds() {
        $menu_id = Request::param('menu_id');
        
        $menuList = Db::name('menu')->select();
        $sub_ids = ',' . implode(getSubIds($menuList, 'menu_id', $menu_id), ',') . ',';
        $family_ids = ',' . implode(family_tree($menuList, 'menu_id', $menu_id), ',')  . ',';
        
        echo json_encode([
            'sub_ids' => $sub_ids,
            'family_ids' => $family_ids
        ]);
    }
}