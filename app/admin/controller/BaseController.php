<?php

namespace app\admin\controller;
use think\exception\HttpResponseException;
use think\facade\View;
use think\facade\Db;
use think\facade\Config;
use think\facade\Request;
use think\facade\Session;
use think\facade\Cookie;
use fast\Auth;
//use think\Response;

class BaseController
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
        //网站配置信息     
        config(setting(),'config');//dump(config());exit;
        //View::assign('web_config', $config);
        //记住密码功能要用到
        if (!Config::get('app.cookie_salt')) {
            $this->error('请配置COOKIE_SALT信息');
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
        $menu = Db::name('auth_rule')->field('id,title,pid,name,icon')->where("islink=1 $menu_where ")->order('o ASC')->select();
        $menu = $this->getMenu($menu);
        View::assign('menu', $menu);


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
    //
    // 以下为新增，为了使用旧版的 success error redirect 跳转  start
    //
    
    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param  mixed     $msg 提示信息
     * @param  string    $url 跳转的URL地址
     * @param  mixed     $data 返回的数据
     * @param  integer   $wait 跳转等待时间
     * @param  array     $header 发送的Header信息
     * @return void
     */
    protected function success($msg = '', string $url = null, $data = '', int $wait = 3, array $header = [])
    {
        if (is_null($url) && isset($_SERVER["HTTP_REFERER"])) {
            $url = $_SERVER["HTTP_REFERER"];
        } elseif ($url) {
            $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : app('route')->buildUrl($url);
        }

        $result = [
            'code' => 1,
            'msg'  => $msg,
            'data' => $data,
            'url'  => $url,
            'wait' => $wait,
        ];

        $type = $this->getResponseType();
        if ($type == 'html'){
            $response = view(config('app.dispatch_success_tmpl'), $result);
        } else if ($type == 'json') {
            $response = json($result);
        }
        throw new HttpResponseException($response);
    }

    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param  mixed     $msg 提示信息
     * @param  string    $url 跳转的URL地址
     * @param  mixed     $data 返回的数据
     * @param  integer   $wait 跳转等待时间
     * @param  array     $header 发送的Header信息
     * @return void
     */
    protected function error($msg = '', string $url = null, $data = '', int $wait = 3, array $header = [])
    {
        if (is_null($url)) {
            $url = request::isAjax()? '' : 'javascript:history.back(-1);';
        } elseif ($url) {
            $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : $this->app->route->buildUrl($url);
        }

        $result = [
            'code' => 0,
            'msg'  => $msg,
            'data' => $data,
            'url'  => $url,
            'wait' => $wait,
        ];

        $type = $this->getResponseType();
        if ($type == 'html'){
            $response = view(config('app.dispatch_error_tmpl'), $result);
        } else if ($type == 'json') {
            $response = json($result);
        }
        throw new HttpResponseException($response);
    }

/**
 * URL重定向
 * @param string $url 重定向的URL地址
 * @param integer $time 重定向的等待时间（秒）
 * @param string $msg 重定向前的提示信息
 * @return void
 */
function redirect($url, $time=0, $msg='') {
    //多行URL地址支持
    $url        = str_replace(array("\n", "\r"), '', $url);
    if (empty($msg))
        $msg    = "系统将在{$time}秒之后自动跳转到{$url}！";
    if (!headers_sent()) {
        // redirect
        if (0 === $time) {
            header('Location: ' . $url);
        } else {
            header("refresh:{$time};url={$url}");
            echo($msg);
        }
        exit();
    } else {
        $str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if ($time != 0)
            $str .= $msg;
        exit($str);
    }
}

    /**
     * 获取当前的response 输出类型
     * @access protected
     * @return string
     */
    protected function getResponseType()
    {
        return request::isJson() || request::isAjax() ? 'json' : 'html';
    }
    
    //
    // 以上为新增，为了使用旧版的 success error redirect 跳转  end
    //

}
