<?php

namespace app\admin\model;
use think\Model;

class Admin extends Model 
{
    protected $pk = 'admin_id';
    protected $insert = ['create_time'];
    
    protected function setCreateTimeAttr($val, $data) {
        return time();
    }
}