<?php
use think\facade\Db;
use think\facade\Session;
error_reporting(E_ERROR | E_PARSE);
// 应用公共文件  打印SQL $model->getLastSql();
/**
 *
 * 获取用户信息
 *
 **/
function member($uid, $field = false)
{
    $model = Db::name('users');
    if ($field) {
        return $model->field($field)->where(array('id' => $uid))->find();
    } else {
        return $model->where(array('id' => $uid))->find();
    }
}
//显示用户所在公司及部门
function user_company($mid){
    $mid = explode(',',$mid);
    $group = '';
    $model = Db::name('company');
    foreach ($mid as $key => $value) {
        $group .= $model->where(array('id'=>$value))->value('cname').',';
        //两个组以上的就显示多个用户组
        if($key>1){
            return '多个属性';exit;
        }
    }
    return substr($group,0, -1);
}
//显示用户所在公司及部门英文
function user_companyen($mid){
    $mid = explode(',',$mid);
    $group = '';

    foreach ($mid as $key => $value) {
        $group .= Db::name('company')->where(array('id'=>$value))->value('ename').',';
        //两个组以上的就显示多个用户组
        if($key>1){
            return '多个属性';exit;
        }
    }
    return substr($group,0, -1);
}
//显示用户组
function user_group($uid){
    $usergroup_access = Db::name('auth_group_access')->where(array('uid'=>$uid))->field('group_id')->select();
    $group = '';

    foreach ($usergroup_access as $key => $v) {
        $group .= Db::name('auth_group')->where(array('id'=>$v))->value('title').',';
        //两个组以上的就显示多个用户组
        if($key>1){
            return '多个用户组';exit;
        }
    }
    return substr($group,0, -1);
}
//获取所有分类的子级id
function category_get_sons($cid, &$array = array())
{
    //获取当前cid下的所有子栏目的id
    $categorys = Db::name("category")->where("pid = {$cid}")->select()->toArray();

    $array = array_merge($array, array($cid));
    foreach ($categorys as $category) {
        category_get_sons($category['id'], $array);
    }
    $data = $array;
    unset($array);
    return $data;

}
/**
 * 管理员操作记录
 * @param $rank 日志类型,1为登录日志
 * @param $log_info 记录信息
 */
function userLog($log_info,$rank = 1,$username = '记住密码用户'){
    $add['name'] = session('user.username')==''?$username:session('user.username');
    $add['desc'] = $log_info;     //操作内容
    $add['ip'] = request()->ip(); //获取客户端IP
    $add['time'] = time();        //记录时间
    $add['rank'] = $rank;         //日志类别,1为登录日志,2登录失败被锁,3管理员操作
    Db::name("user_log")->insert($add);
}
/**
 * 用户访问记录
 * @param $rank 日志类型,1为访问日志
 * @param $log_info 访问记录信息
 */
function userLogin($rank = 1,$username = '访客'){
    $protocol = (!empty($_SERVER['HTTP_FROM_HTTPS']) && $_SERVER['HTTP_FROM_HTTPS'] !== 'off') ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $add['name'] = session('user.username')==''?$username:session('user.username');//登录用户名
    $add['url'] = $url;//访问的url;
    $add['os'] = get_os();     //操作系统
    $add['broswer'] = get_broswer()[0];     //浏览器
    $add['broswerb'] = get_broswer()[1];     //浏览器版本号
    $add['ip'] = request()->ip(); //获取客户端IP
    $add['date'] = date('Y-m-d',time());//记录时间
    $add['time'] = time();        //记录时间
    $add['rank'] = $rank;         //日志类别,1为访问日志
    $model = Db::name("user_login");
    $state = $model->where(array('url'=>$add['url'],'ip'=>$add['ip'],'date'=>$add['date']))->find();
    if(!$state>0){
        $model->insert($add);
    }
    
}
/**
 * 用户排序表--排序规则
 * @param $id 传入数字
 */
function user_order($id){
switch($id){
    case '0':
        return 'id asc';
    break;
    case '1':
        return 'id desc';
    break;
    case '2':
        return 'reg_time asc';
    break;
    case '3':
        return 'reg_time desc';
    break;
    case '4':
        return 'login_time asc';
    break;
    case '5':
        return 'login_time desc';
    break;
    default:
        return 'id asc';
    }
}
/**
 * 函数：加密
 * @param string            密码
 * @return string           加密后的密码
 */
function password($password)
{
    /*
    *后续整强有力的加密函数
    */
    return md5('X' . $password . 'F');

}
/*
 * 函数：网站配置获取函数
 * @param  string $k      可选，配置名称
 * @return array          用户数据
*/
function setting($k = 'all')
{
    $cache = cache($k);
    //如果缓存不为空直接返回
    if (null != $cache) {
        return $cache;
    }
    $data = '';
    $setting = Db::name('setting');
    //判断是否查询全部设置项
    if ($k == 'all') {
        $setting = $setting->field('k,v')->select();
        foreach ($setting as $v) {
            $config[$v['k']] = $v['v'];
        }
        $data = $config;

    } else {
        $result = $setting->where("k='{$k}'")->find();
        $data = $result['v'];

    }
    //建立缓存
    if ($data) {
        cache($k, $data);
    }
    return $data;
}
/**
 * 增加日志
 * @param $log
 * @param bool $name
 */

function addlog($log, $name = false)
{
    $Model = Db::name('log');
    if (!$name) {        
        $uid = Session::get('uid');
        if ($uid) {
            $user = Db::name('member')->field('user')->where(array('uid' => $uid))->find();
            $data['name'] = $user['user'];
        } else {
            $data['name'] = '';
        }
    } else {
        $data['name'] = $name;
    }
    $data['t'] = time();
    $data['ip'] = $_SERVER["REMOTE_ADDR"];
    $data['log'] = $log;
    $Model->insert($data);
}
//图片上传
function UpImage($callBack = "image", $width = 100, $height = 100, $image = "")
{

    echo '<iframe scrolling="no" frameborder="0" border="0" onload="this.height=this.contentWindow.document.body.scrollHeight;this.width=this.contentWindow.document.body.scrollWidth;" width=' . $width . ' height="' . $height . '"  src="' . U('Upload/uploadpic',
            array('Width' => $width, 'Height' => $height, 'BackCall' => $callBack)) . '"></iframe>
         <input type="hidden" ' . 'value = "' . $image . '"' . 'name="' . $callBack . '" id="' . $callBack . '">';
}
/**
 * 函数：格式化字节大小
 * @param  number $size 字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '')
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) {
        $size /= 1024;
    }
    return round($size, 2) . $delimiter . $units[$i];
}
/**  
 * 获取客户端浏览器信息
 * @param   null  
 * @author  https://hotxf.com
 * @return  string   
 */  
function get_broswer(){
    $sys = $_SERVER['HTTP_USER_AGENT'];  //获取用户代理字符串  
    if (stripos($sys, "Firefox/") > 0) {  
        preg_match("/Firefox\/([^;)]+)+/i", $sys, $b);  
        $exp[0] = "Firefox";  
        $exp[1] = $b[1];    //获取火狐浏览器的版本号  
    } elseif (stripos($sys, "Maxthon") > 0) {  
        preg_match("/Maxthon\/([\d\.]+)/", $sys, $aoyou);  
        $exp[0] = "傲游";  
        $exp[1] = $aoyou[1];  
    } elseif (stripos($sys, "MSIE") > 0) {  
        preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);  
        $exp[0] = "IE";  
        $exp[1] = $ie[1];  //获取IE的版本号  
    } elseif (stripos($sys, "OPR") > 0) {  
        preg_match("/OPR\/([\d\.]+)/", $sys, $opera);  
        $exp[0] = "Opera";  
        $exp[1] = $opera[1];    
    } elseif(stripos($sys, "Edge") > 0) {  
        //win10 Edge浏览器 添加了chrome内核标记 在判断Chrome之前匹配  
        preg_match("/Edge\/([\d\.]+)/", $sys, $Edge);  
        $exp[0] = "Edge";  
        $exp[1] = $Edge[1];  
    } elseif (stripos($sys, "Chrome") > 0) {  
        preg_match("/Chrome\/([\d\.]+)/", $sys, $google);  
        $exp[0] = "Chrome";  
        $exp[1] = $google[1];  //获取google chrome的版本号  
    } elseif(stripos($sys,'rv:')>0 && stripos($sys,'Gecko')>0){  
        preg_match("/rv:([\d\.]+)/", $sys, $IE);  
        $exp[0] = "IE";  
        $exp[1] = $IE[1];  
    }else {  
        $exp[0] = "未知浏览器";  
        $exp[1] = "";   
    }  
    return $exp;  
}
/**  
 * 获取客户端操作系统信息,包括win10 
 * @param   null  
 * @author  http://hotxf.com
 * @return  string   
 */  
function get_os(){
    $agent = $_SERVER['HTTP_USER_AGENT'];
    $os = false;
    if (preg_match('/win/i', $agent) && preg_match('/nt 6.0/i', $agent))  
    {  
      $os = 'Windows Vista';  
    }  
    else if (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent))  
    {  
      $os = 'Windows 7';  
    }  
      else if (preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent))  
    {  
      $os = 'Windows 8';  
    }else if(preg_match('/win/i', $agent) && preg_match('/nt 10.0/i', $agent))  
    {  
      $os = 'Windows 10';#添加win10判断  
    }else if (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent))  
    {  
      $os = 'Windows XP';  
    }  
    else if (preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent))  
    {  
      $os = 'Windows 2000';  
    }  
    else if (preg_match('/win/i', $agent) && preg_match('/nt/i', $agent))  
    {  
      $os = 'Windows NT';  
    }  
    else if (preg_match('/win/i', $agent) && preg_match('/32/i', $agent))  
    {  
      $os = 'Windows 32';  
    }  
    else if (preg_match('/linux/i', $agent))  
    {  
      $os = 'Linux';  
    }  
    else if (preg_match('/unix/i', $agent))  
    {  
      $os = 'Unix';  
    }  
    else if (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent))  
    {  
      $os = 'SunOS';  
    }  
    else if (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent))  
    {  
      $os = 'IBM OS/2';  
    }  
    else if (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent))  
    {  
      $os = 'MAC';  
    }  
    else if (preg_match('/PowerPC/i', $agent))  
    {  
      $os = 'PowerPC';  
    }  
    else if (preg_match('/AIX/i', $agent))  
    {  
      $os = 'AIX';  
    }  
    else if (preg_match('/HPUX/i', $agent))  
    {  
      $os = 'HPUX';  
    }  
    else if (preg_match('/NetBSD/i', $agent))  
    {  
      $os = 'NetBSD';  
    }  
    else if (preg_match('/BSD/i', $agent))  
    {  
      $os = 'BSD';  
    }  
    else if (preg_match('/OSF1/i', $agent))  
    {  
      $os = 'OSF1';  
    }  
    else if (preg_match('/IRIX/i', $agent))  
    {  
      $os = 'IRIX';  
    }  
    else if (preg_match('/FreeBSD/i', $agent))  
    {  
      $os = 'FreeBSD';  
    }  
    else if (preg_match('/teleport/i', $agent))  
    {  
      $os = 'teleport';  
    }  
    else if (preg_match('/flashget/i', $agent))  
    {  
      $os = 'flashget';  
    }  
    else if (preg_match('/webzip/i', $agent))  
    {  
      $os = 'webzip';  
    }  
    else if (preg_match('/offline/i', $agent))  
    {  
      $os = 'offline';  
    }  
    else  
    {  
      $os = '未知操作系统';  
    }  
    return $os;    
}
