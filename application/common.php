<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 将二维数组组织成树状结构, 数组必须包含pid字段
 * @param type $arr       目标数组
 * @param type $column    目标数组的主键字段
 * @param type $pid       数组pid的起始点
 * @param type $deep      层次深度
 * @param type $is_clear
 * @return type
 */
function array_tree($arr, $column = 'id', $pid = 0, $deep = 0, $is_clear = true) {
    if (empty($arr) || !isset($arr[0]['pid'])) {
        return [];
    }
    
    static $tree = [];
    if ($is_clear) {
        $tree = [];
    }
    
    foreach ($arr as $v) {
        if ($v['pid'] == $pid) {
            $v['deep'] = $deep;
            $tree[] = $v;
            array_tree($arr, $column, $v[$column], $deep+1, false);            
        }
    }
    
    return $tree;
}

/**
 * 将数组重新组织成嵌套结构, 要求数组必须为二维或多维, 且必须有pid字段
 * @param array $arr        目标数组
 * @param string $id_name   目标数组的主键字段
 * @param integer $pid      pid
 * @return array
 */
function array_nested($arr, $column = 'id', $pid = 0) {

    if (empty($arr) || !isset($arr[0]['pid'])) {
        return [];
    }
    
    $nested = [];
    foreach($arr as $v) {
        if ($v['pid'] == $pid) {
            $v['son'] = array_nested($arr, $column, $v[$column]);
            $nested[] = $v;
        }
    }
    
    return $nested;
}


function dataTree($arr, $column_name = 'name', $repeat_str = '---- ', $deep = 0) {
    static $tree = [];
    
    foreach ($arr as $v) {
        $v[$column_name] = str_repeat($repeat_str, $deep) . $v[$column_name];
        $tree[] = $v;
        if (!empty($v['son'])) {
            dataTree($v['son'],$column_name, $repeat_str, $deep + 1);
        }
    }
    
    return $tree;
}

/**
 * 获取指定id的子孙id
 * @param type $arr         
 * @param type $id_name
 * @param type $pid
 * @return type
 */
function getSubIds($arr, $column = 'id', $pid = 0) {
    $take = [$pid];
    $subIds = [];
    
    while(!empty($take)) {
        $flag = false;
        foreach ($arr as $k => $v) {
            if ($v['pid'] == $pid) {
                $subIds[] = $v[$column];
                array_push($take, $v[$column]);
                $pid = $v[$column];
                
                unset($arr[$k]);
                
                $flag = true;
            }
        }
        
        if (!$flag) {
            array_pop($take);
            $pid = end($take);
        }
    }
    
    return $subIds;
}

/**
 * 获取指定id的家谱树
 * @staticvar array $tree
 * @param type $arr
 * @param type $id_name
 * @param type $id
 * @return type
 */
function family_tree($arr, $id_name = 'id', $id) 
{
    static $tree = [];
    foreach ($arr as $k => $v) {
        if ($v[$id_name] == $id) {
            $tree[] = $v[$id_name];
            if ($v['pid'] > 0) {
                family_tree($arr, $id_name, $v['pid']);
            }
        }
    }
    
    return $tree;
}

/**
 * 加密函数
 * @param type $str
 * @return type
 */
function encrypt($str) {
    return md5(config('auth_code') . $str);
}

/**
 * 验证验证码是否正确
 * @param type $code    用户验证码
 * @param type $reset   是否重置验证码
 * @param type $id      验证码标识
 * @return bool     用户验证码是否正确
 */
function check_captcha($code, $reset = true, $id = '') 
{
    $captcha = new \think\captcha\Captcha(['reset'=>$reset]);
    return $captcha->check($code, $id);
}