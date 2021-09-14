<?php

namespace app\admin\controller;

class Login extends Common 
{
    public function login()
    {
        echo md5('GG_shop1234abcd');
        return $this->fetch();
    }
    
    public function checkout() {
        echo md5('GG_shop1234abcd');
    }
}