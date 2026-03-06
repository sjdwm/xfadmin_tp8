<?php
namespace app\home\controller;
use think\facade\View;
class Error {
    public function __call($method, $args){

        header( " HTTP/1.0  404  Not Found" );
        $nav[0] = array('id'=>0,'pid'=>0,'title'=>'首页Home','name'=>'Index/index','url'=>'/');
        $nav[1] = array('id'=>1,'pid'=>0,'title'=>'用户登录Login','name'=>'Publicc/login','url'=>'/Publicc/login');
        //$nav[2] = array('id'=>2,'pid'=>0,'title'=>'用户注册Reg','name'=>'Public/reg','url'=>'/Public/reg');
        $nav[3] = array('id'=>3,'pid'=>0,'title'=>'帮助Help','name'=>'Publicc/help','url'=>'/Publicc/help');
       
        View::assign('cate', $nav);//导航
        return View::fetch('Error/index');

    }
    public function index(){
        header('HTTP/1.0 404 Not Found');
        $nav[0] = array('id'=>0,'pid'=>0,'title'=>'首页Home','name'=>'Index/index','url'=>'/');
        $nav[1] = array('id'=>1,'pid'=>0,'title'=>'用户登录Login','name'=>'Publicc/login','url'=>'/Publicc/login');
        //$nav[2] = array('id'=>2,'pid'=>0,'title'=>'用户注册Reg','name'=>'Publicc/reg','url'=>'/Publicc/reg');
        $nav[3] = array('id'=>3,'pid'=>0,'title'=>'帮助Help','name'=>'Publicc/help','url'=>'/Publicc/help');
       
        View::assign('cate', $nav);//导航
        return View::fetch('Error/index'); 
    }
}