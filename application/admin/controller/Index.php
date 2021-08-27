<?php
namespace app\admin\controller;
use Db;

class Index extends Privilege
{
    public function index()
    {
        
        return $this->fetch();
    }
    
    public function top()
    {
        return $this->fetch();
    }
    
    public function menu()
    {
        $this->assign('menuList', []);
        return $this->fetch();
    }
    
    public function main()
    {
        
        return $this->fetch();
    }
    
    public function drag()
    {
        return $this->fetch();
    }
}
