<?php

namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\facade\View;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\Cookie;
use think\facade\Config;
use think\facade\Cache;

class Login extends BaseController{
    public function index()
    {

        $uid = Session::get('uid');
        if ($uid>0) {
            $this->error('您已经登录,正在跳转到主页', url("index/index"));
        }

        return View::fetch();
    }

    public function login()
    {
 
        $data = input();//dump($data);exit;
        if(!captcha_check($data['verify'])){
         // 验证失败
            //return '验证失败';
            $this->error('验证码错误！', url("login/index"));
        };
        $username = isset($data['user']) ? trim($data['user']) : '';
        $password = isset($data['password']) ? trim($data['password']) : '';
        $remember = isset($data['remember']) ? $data['remember'] : 0;
        if ($username == '') {
            $this->error('用户名不能为空！', U("login/index"));
        } elseif ($password == '') {
            $this->error('密码必须！', U("login/index"));
        }

        //验证用户名
        $user = Db::name("users")->field('id,name,ename,username,password,head_img,mid,gid,email,lang,lock,token,black_time')->where(array('username' => $username, 'stop' =>0))->find();     
        //如果没有
        if (is_null($user)) {
            $this->error('用户名不存在,或被禁用', url("login/index"));
            return;
        }
        //判断账号是否锁
        if($user['black_time']+60>time()){
            $s = ($user['black_time']+60)-time();
            $this->error("该账号已锁，请 {$s} 秒后再试！");
            exit;
        }
        if ($user['password'] == password($password)) {
            $salt = Config::get('app.cookie_salt');
            $ip = request()->ip();
            $ua = $_SERVER['HTTP_USER_AGENT'];
            //dump($user['uid']);exit;
            Session::set('user', $user);
            //加密cookie信息
            $auth = password($user['id'].$user['username'].$user['password'].$ip.$ua.$salt);
            if ($remember) {                             
                Cookie::set('auth', $auth, 3600 * 24 * 30);//记住我30天  
                Cookie::set('uid', $user['id'], 3600 * 24 * 30);//记住我
            } else {
                /*Cookie::set('auth', $auth, 3600);//一小时
                Cookie::set('uid', $user['id'], 3600);//一小时*/
                
            }
            //修改登录时间和IP
            Db::name("users")->where(array('id'=>$user['id']))->save(array('login_time'=>time(),'login_ip'=>$ip));
            userLog('后台登录成功,用户名:'.$user['username'],3);
            
            $url = url('index/index');//dump($url);exit;           
            return redirect($url);
            
        } else {
            if($user['lock']<5){
                $da['lock'] =$user['lock']+1;
                $s = 5-$user['lock'];
                Db::name("users")->where(array('username'=>$username))->save($da);
                $this->error('密码错误！您还有'.$s.'次机会', url("login/index"));

            }else{
                $time1=time();
                $d1['black_time']=$time1;
                $d1['lock'] = 0;
                userLog('后台登录失败5次,用户名:'.$username,4);
                Db::name("users")->where(array('username'=>$username))->save($d1);
                $this->error('您的账号已被锁，请一分钟后再试！', url("login/index"));
            }
            
        }
    }
    //退出登录
    public function logout()
    {

        cookie('auth', null);
        cookie('uid', null);
        session(null);
        $url = url("login/index");        
        return redirect($url);
    }
    //清除缓存
    public function clear()
    {

        $dir = app()->getRootPath().'runtime\admin\temp';
        $this->rmdirr($dir);
        Cache::clear();
        $this->success('系统缓存清除成功！');
    }

    //递归删除缓存信息

    public function rmdirr($dirname)
    {
        if (!file_exists($dirname)) {
            return false;
        }
        if (is_file($dirname) || is_link($dirname)) {
            return unlink($dirname);
        }
        $dir = dir($dirname);
        if ($dir) {
            while (false !== $entry = $dir->read()) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                //递归
                $this->rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
            }
        }
        $dir->close();
        return rmdir($dirname);
    }

}
