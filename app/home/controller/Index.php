<?php

namespace app\home\controller;

use think\facade\View;
use think\facade\Db;
use fast\Page;
class Index 
{

    public function index()
    {

       
        return '小风博客www.hotxf.com 后台：/index.php/admin'.'<a href="../index.php/admin/" class="btn btn_submit J_install_btn">进入后台</a>';

    }
   
    
}
