<?php

namespace app\admin\controller;
use app\BaseController;

use think\facade\View;
use think\facade\Db;
use think\facade\Config;
use think\facade\Request;
use think\facade\Session;
use think\facade\Cookie;
use fast\Auth;
//use think\Response;

class ComController extends BaseController
{
    public $USER;

    public function __construct()
    {
     
        /**
         * 不需要登录控制器
         */
        if (in_array(request()->controller(), array("Login"))) {
            return true;
        }
        //dump(request()->controller());exit;
        //检测是否登录
        $flag =  $this->check_login();//dump($flag);exit;
        $url = url("Login/index");
        if (!$flag) {
                       
            $this->redirect($url);
           
        }
        
        $UID = $this->USER['uid'];
        $prefix = Config::get('database.connections.mysql.prefix');
        $userinfo = Db::query("SELECT * FROM {$prefix}auth_group g left join {$prefix}auth_group_access a on g.id=a.group_id where a.uid=$UID");
        $Auth = new Auth();
        $module_name = app('http')->getName();//模块名称
        $allow_controller_name = array('Upload');//放行控制器名称
        $allow_action_name = array();//放行函数名称
        if ($userinfo[0]['group_id'] != 1 && !$Auth->check($module_name.'/'.request()->controller() . '/' . request()->action(),
                $UID) && !in_array(request()->controller(), $allow_controller_name) && !in_array(request()->action(),
                $allow_action_name)
        ) {
            $this->error('没有权限访问本页面!');
        }
        
        $current_action_name = request()->action() == 'edit' ? "index" : request()->action();
        $current = Db::query("SELECT s.id,s.title,s.name,s.tips,s.pid,p.pid as ppid,p.title as ptitle FROM {$prefix}auth_rule s left join {$prefix}auth_rule p on p.id=s.pid where s.name='" . $module_name.'/'.request()->controller() . '/' . $current_action_name . "'");
        View::assign('current', $current[0]);


        $menu_access_id = $userinfo[0]['rules'];

        if ($userinfo[0]['group_id'] != 1) {

            $menu_where = "AND id in ($menu_access_id)";

        } else {

            $menu_where = '';
        }
        $menu = Db::name('auth_rule')->field('id,title,pid,name,icon')->where("cate=1 and islink=1 $menu_where ")->order('o ASC')->select();
        $menu = $this->getMenu($menu);
        View::assign('menu', $menu);

        parent::__construct();
    }

    protected function getMenu($items, $id = 'id', $pid = 'pid', $son = 'children')
    {
        $tree = array();
        $tmpMap = array();
        //修复父类设置islink=0，但是子类仍然显示的bug
        foreach( $items as $item ){
            if( $item['pid']==0 ){
                $father_ids[] = $item['id'];
            }
        }
        //----
        foreach ($items as $item) {
            $tmpMap[$item[$id]] = $item;
        }

        foreach ($items as $item) {
            //修复父类设置islink=0，但是子类仍然显示的bug,php8增加$father_ids<> ''判断
            if( $item['pid']<>0 && $father_ids<> '' && !in_array($item['pid'],$father_ids)){
                continue;
            }
            //----
            if (isset($tmpMap[$item[$pid]])) {
                $tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
            } else {
                $tree[] = &$tmpMap[$item[$id]];
            }
        }
        return $tree;
    }

    public function check_login(){
        
        $flag = false;
        $salt = Config::get('app.cookie_salt');
        $ip = request()->ip();
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $auth = Cookie::get('auth');
        $uid = Session::get('user.id');
        if (!$uid) {
            $uid = Cookie::get('uid');
            $user = Db::name('users')->field('id,name,ename,username,password,head_img,mid,gid,email,lang,lock,token')->where(array('id' =>$uid))->find();
            //dump($user);exit;
            if ($user) {
                if ($auth ==  password($uid.$user['username'].$user['password'].$ip.$ua.$salt)) {
                    $flag = true;
                    $this->USER = array('uid'=>$user['id']);
                                      
                    Session::set('user', $user);
                    userLog('后台登录成功(记住密码),用户名:'.$user['username'],1);
                }
            }
        }else{
            $flag = true;
            $this->USER = array('uid'=>$uid);
        }
        return $flag;
    }
   
}
