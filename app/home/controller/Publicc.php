<?php

namespace app\home\controller;
use app\Common\Controller\BaseController;
use think\exception\HttpResponseException;
use think\facade\View;
use think\facade\Db;
use think\facade\Config;
use think\facade\Request;
use think\facade\Session;
use think\facade\Cookie;
use fast\Auth;
class Publicc extends BaseController
{

    public function login(){
        //检测用户是否登录
        if (!empty(session('user.id'))) {
           // return redirect(url('Index/index'));
            
        }
        $nav[0] = array('id'=>0,'pid'=>0,'title'=>'首页Home','name'=>'Index/index','url'=>'/');
        $nav[1] = array('id'=>1,'pid'=>0,'title'=>'用户登录Login','name'=>'Publicc/login','url'=>'/Publicc/login');
        $nav[2] = array('id'=>2,'pid'=>0,'title'=>'用户注册Reg','name'=>'Publicc/reg','url'=>'/Publicc/reg');
        $nav[3] = array('id'=>3,'pid'=>0,'title'=>'帮助Help','name'=>'Publicc/help','url'=>'/Publicc/help');
        View::assign('cate', $nav);//导航
        return View::fetch();
    }
    //执行登录
    public function dologin(){
        //获取表单数据
        $username = trim(input('post.username'));
        $password = trim(input('post.password'));
        $remember = input('post.remember',0);
        if($username =='' or $password==''){
            $this->error('用户名密码不能为空', url('Publicc/login'));
        }
        //用户名查找当家系统里是否存在该用户
        $user_info = Db::name('users')->where(array('username'=>$username))->find();
        //调用ERP接口验证用户登录
        $d = app('app\api\controller\erp')->login($username,$password);
        $da = json_decode($d,true);
        if($da['header']['session']==''){            
            //登录不成功,直接返回错误信息,如果ERP账号登录失败,启用当前系统用户验证登录
            if($user_info['id']>0 and $user_info['password']==password($password)){

            }else{
                $this->error($da['header']['message'], url('Publicc/login'));
            }
            
        }
        
        //如果没有,调用管理账号查询登录用户的信息,再写入当前系统用户表
        if (is_null($user_info)) {
            app('app\api\controller\erp')->login(Config('app.erp_user'),Config('app.erp_pwd'));//账号管理API专用账号
            $name = app('app\api\controller\erp')->userList($username);
            $name = json_decode($name,true);
            $data['id'] = $name['Rows'][0][0];
            $data['username'] = $name['Rows'][0][1];
            $data['job'] = $name['Rows'][0][2];            
            $data['name'] = $name['Rows'][0][3];
            $data['stop'] = $name['Rows'][0][4]=='正常'?'0':'1';
            $data['mid'] = 1;//公司
            $data['gid'] = $name['Rows'][0][5];
            $data['password'] = password($password);
            $data['head_img'] = '/static/xfadmin/img/1.png';
            $data['reg_time'] = time();        
            $uid = Db::name('users')->insert($data);
            $group=array('uid'=>$data['id'],'group_id'=>3);//普通用户组3
            Db::name('AuthGroupAccess')->insert($group);
            $user_info = array('id'=>$data['id'],'username'=>$data['username'],'password'=>$data['password']);
        }else{
            //如果系统里有他的数据,看看密码有没有改变,改变了更新下密码
            if($user_info['password']!=password($password)){
               Db::name('users')->where(array('username'=>$username))->save(array('password'=>password($password))); 
            }
        }
            //登录成功,写入用户信息到session
            $salt = Config::get('app.cookie_salt');
            $ip = request()->ip();
            $ua = $_SERVER['HTTP_USER_AGENT'];
            //dump($user['uid']);exit;
            Session::set('user', $user_info);
            //加密cookie信息
            $auth = password($user_info['id'].$user_info['username'].$user_info['password'].$ip.$ua.$salt);
            if ($remember) {                             
                Cookie::set('auth', $auth, 3600 * 24 * 30);//记住我30天  
                Cookie::set('uid', $user['id'], 3600 * 24 * 30);//记住我
            } else {
                /*Cookie::set('auth', $auth, 3600);//一小时
                Cookie::set('uid', $user['id'], 3600);//一小时*/
                
            }
            //修改登录时间和IP
            Db::name("users")->where(array('id'=>$user_info['id']))->save(array('login_time'=>time(),'login_ip'=>$ip));
            userLog('前台登录成功,用户名:'.$username,1);
                
        
        //跳转到首页
        //$this->redirect('Index/index', '', 1, 'Login....');
        $url = cookie('url');
        if($url){
            //if($url=='https://nas.hotxf.com/' || $url=='https://www.maxim-eip.com/'){
               
                //$this->success('登录成功,正在跳转 Login is successful, jumping!',U('Index/index'));
            //}else{
                   
                return redirect($url);
            //}
            
        }else{
            //$this->success('登录成功,正在跳转 Login is successful, jumping!',U('Index/index'));
            $this->success('登录成功,正在跳转 Login is successful, jumping!',url('Index/index'));
        }
        
    }
    //执行退出
    public function logout(){
        cookie('auth', null);
        cookie('uid', null);
        session(null);
        $this->success('登出成功 Logout success','login');
    }

    
}
