<?php

namespace app\home\controller;
use app\BaseController;
//use think\exception\HttpResponseException;
use think\facade\View;
use think\facade\Db;
use think\facade\Config;
use think\facade\Request;
use think\facade\Session;
use think\facade\Cookie;
use fast\Auth;
use fast\Data;
//use think\Response;

class ComController extends BaseController
{
    public $USER;

    public function __construct()
    {
     
        /**
         * 不需要登录控制器
         */
        if (in_array(request()->controller(), array("Publicc"))) {
            return true;
        }
        //dump(request()->controller());exit;
        //检测是否登录
        $flag =  $this->check_login();//dump($flag);exit;       
        if (!$flag) {
            //记录未登录时访问的地址,登录后直接跳转,有效期180秒
            $url = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
            cookie('url', $url,180);           
            $this->redirect(url("Publicc/login"));
           
        }
        //写入登录日志
        /*if(session('user.date') != date('Y-m-d',time())){
            userLog('前台登录成功(免登录的),用户名:'.session('user.username'),1);
            session('user.date',date('Y-m-d',time()));
        }*/
        userLogin();//用户访问url日志
        $uid = session('user.id');
        //查看用户是否有修改密码,没有修改就提示修改,默认:123456
        $pwd = Db::name('users')->where(array('id' => $uid))->value('password');
        if($pwd=='d827a589220b65660ef68ae105e55a81'){
            View::assign('repwd',1);
        }       
        $UID = $this->USER['uid'];
        //$prefix = Config::get('database.connections.mysql.prefix');
        //$userinfo = Db::query("SELECT * FROM {$prefix}auth_group g left join {$prefix}auth_group_access a on g.id=a.group_id where a.uid=$UID");
        $userinfo = Db::name("auth_group")->alias('g')->leftJoin('auth_group_access a','g.id=a.group_id')->where('a.uid',$UID)->select()->toArray();
        $Auth = new Auth();
        $module_name = app('http')->getName();//模块名称
        $allow_controller_name = array('Upload');//放行控制器名称
        $allow_action_name = array();//放行函数名称       
        if ($userinfo[0]['group_id'] != 1 && !$Auth->check(request()->controller() . '/' . request()->action(),
                $UID) && !in_array(request()->controller(), $allow_controller_name) && !in_array(request()->action(),
                $allow_action_name)
        ) {
           
            $this->error('没有权限访问本页面!');
        }
        //首页菜单
        $cate_list = Db::name('auth_rule')->order('o ASC')->where(array('cate'=>2,'islink'=>1))->select()->toArray();
        
        $data = Data::channelLevel($cate_list,0,'&nbsp;','id');
        //用户组1为超级管理员，跳过权限
        if($userinfo[0]['group_id'] != 1){
        // 显示有权限的菜单
        foreach ($data as $k => $v) {
                if ($Auth->check($v['name'],$uid)) {
                    foreach ($v['_data'] as $m => $n) {
                        if(!$Auth->check($n['name'],$uid)){
                            unset($data[$k]['_data'][$m]);
                        }
                        foreach ($n['_data'] as $mm => $nn) {
                        if(!$Auth->check($nn['name'],$uid)){
                            unset($data[$k]['_data'][$m]['_data'][$mm]);
                        }
                    }
                    }
                }else{
                    // 删除无权限的菜单
                    unset($data[$k]);
                }
            }//dump($data);exit;
        }
        View::assign('cate', $data);//导航

        parent::__construct();
    }
    //不存在的方法访问404
    public function __call($method, $args){

        header( " HTTP/1.0  404  Not Found" );
        $nav[0] = array('id'=>0,'pid'=>0,'title'=>'首页Home','name'=>'Index/index','url'=>'/');
        $nav[1] = array('id'=>1,'pid'=>0,'title'=>'用户登录Login','name'=>'Publicc/login','url'=>'/Publicc/login');
        //$nav[2] = array('id'=>2,'pid'=>0,'title'=>'用户注册Reg','name'=>'Public/reg','url'=>'/Public/reg');
        $nav[3] = array('id'=>3,'pid'=>0,'title'=>'帮助Help','name'=>'Publicc/help','url'=>'/Publicc/help');
       
        View::assign('cate', $nav);//导航
        return View::fetch('Error/index');

    }
    //登录状态验证
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
                    $user['date']=date('Y-m-d',time());                
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
