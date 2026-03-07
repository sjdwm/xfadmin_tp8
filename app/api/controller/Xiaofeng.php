<?php

namespace app\api\controller;
use think\facade\Config;
use think\facade\Request;
use think\facade\Db;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException; 
use app\validate\User;
use think\exception\ValidateException;
class xiaofeng 
{
    // 定义配置项
    private $JWT_SECRET;
    private $ACCESS_TOKEN_EXPIRES_IN;
    private $REFRESH_TOKEN_EXPIRES_IN;   
    // 构造函数
    public function __construct(){
    // Token JWT 密钥
    $this->JWT_SECRET = 'WFskbrlnd+Yo01h0GAq+BQTXY1sa+8jrbICs9kB9R/Q=';
    //Token 有效期（秒）
    $this->ACCESS_TOKEN_EXPIRES_IN = 7200;// 2小时
    $this->REFRESH_TOKEN_EXPIRES_IN = 2592000;// 30天        

}
/**
 * refresh 用户登录接口
 * POST /api/xiaofeng/login
 * 使用api 用户登录返回 Token
 */
    public function login(){
        $data = TrimArray(input());
        // 验证时间戳（防止重放攻击）
        if (abs(time() - $data['timestamp']) > 300) {
            return json(array('code'=>400,'msg'=>'请求已过期','data'=>''));
        }
        // 验证手机号（必填）
        if (empty($data['phone'])) {
            return json(array('code'=>400,'msg'=>'手机号不能为空','data'=>''));
        }
        // 验证手机号格式
        if (!preg_match('/^1[3-9]\d{9}$/', $data['phone'])) {
            return json(array('code'=>400,'msg'=>'手机号格式不正确','data'=>''));

        }
        //使用手机号查询用户信息
        $user_info = Db::name('users')->where(array('phone'=>$data['phone']))->find();
        if (!$user_info>0) {
            return json(array('code'=>400,'msg'=>'该手机号未注册','data'=>''));
        }
        //判断账号是否锁
        if($user_info['black_time']+60>time()){
            $s = ($user_info['black_time']+60)-time();
            return json(array('code'=>400,'msg'=>"该账号已锁，请 {$s} 秒后再试！",'data'=>''));
        }
        // 可以空密码登录,不为空就验证密码
        if (!empty($user_info['password'])) {
            if (empty($data['password'])) {
                return json(array('code'=>400,'msg'=>"请输入密码",'data'=>''));
            }
            // 使用 password自定义方法哈希密码
            if ($user_info['password']!=password($data['password'])) {
                
                if($user_info['lock']<5){
                    $s = 5-$user_info['lock'];
                    Db::name("users")->where(array('id'=>$user_info['id']))->inc('lock', 1)->save();
                    return json(array('code'=>400,'msg'=>'密码错误！您还有'.$s.'次机会','data'=>''));
                }else{
                    userLog('后台登录失败5次,用户名:'.$data['phone'],4);
                    Db::name("users")->where(array('id'=>$user_info['id']))->save(array('black_time'=>time(),'lock'=>0));
                    return json(array('code'=>400,'msg'=>'您的账号已被锁，请一分钟后再试！','data'=>''));
                }
            }
            
        }
            //登录成功,返回Token
            //修改登录时间和IP,在线状态
            $ip = request()->ip();
            Db::name("users")->where(array('id'=>$user_info['id']))->save(array('login_time'=>time(),'login_ip'=>$ip));
            userLog('前台登录成功,用户名:'.$user_info['name'],1);
            // 生成 Access Token（2小时有效）
                $access = [
                    'userId' => $user_info['id'],
                    'phone' => $user_info['phone'],
                    'nickname' => $user_info['name'],
                    'type' => 'access',
                    'iat' => time(),
                    'exp' => time() + $this->ACCESS_TOKEN_EXPIRES_IN  // 24小时后过期
                ];
                $accessToken = JWT::encode($access, $this->JWT_SECRET, "HS256");  //生成了 token
                // 生成 Refresh Token（30天有效）
                $refresh = [
                    'userId' => $user_info['id'],
                    'phone' => $user_info['phone'],
                    'nickname' => $user_info['name'],
                    'type' => 'refresh',
                    'iat' => time(),
                    'exp' => time() + $this->REFRESH_TOKEN_EXPIRES_IN  // 30天后过期
                ];
                $refreshToken = JWT::encode($refresh, $this->JWT_SECRET, "HS256");  //生成了 token
                // 返回响应
                return json([
                    'code' => 200,
                    'msg' => '登录成功',
                    'data' => [
                        'userId' => (int)$user_info['id'],
                        'phone' => $user_info['phone'],
                        'nickname' => $user_info['name'],
                        'hasPassword' => !empty($user_info['password']),
                        'accessToken' => $accessToken,
                        'refreshToken' => $refreshToken,
                        'tokenType' => 'Bearer'
                    ]
                ]);
                
        
    }
/**
 * refresh 用户注册接口
 * POST /api/xiaofeng/register
 * 使用api 用户登录返回 Token
 */
    public function register(){
        $da = TrimArray(input());      
        // 验证时间戳（防止重放攻击）
        if (abs(time() - $da['timestamp']) > 300) {
            return json(array('code'=>400,'msg'=>'请求已过期','data'=>''));
        }
        // 验证手机号格式
        if (!preg_match('/^1[3-9]\d{9}$/', $da['phone'])) {
            return json(array('code'=>400,'msg'=>'手机号格式不正确','data'=>''));
        }
        // 验证昵称（必填）
        if (empty($da['nickname'])) {
            return json(array('code'=>400,'msg'=>'昵称不能为空','data'=>''));
        }
        // 验证设备ID（必填）
        if (empty($da['deviceId'])) {
            return json(array('code'=>400,'msg'=>$da['deviceId'],'data'=>''));
        }else{
            // 1. 检查前缀是否为 "DEV"（严格区分大小写）
            if (substr($da['deviceId'], 0, 3) !== 'dev') {
                return json(['code' => 400, 'msg' => '无效的设备ID格式']);
            }
            //2. 查看当前设备ID已经注册了几个账号,最多只能5个（防止批量注册）
            if (Db::name('users')->where("device_id",$da['deviceId'])->count()>5) {
                return json(array('code'=>400,'msg'=>'当前设备ID已经注册了5个账号,不可再注册了！','data'=>''));
            }
            
        }
        //查看当前IP已经注册了几个账号,最多只能5个（防止批量注册）
        $ip = request()->ip();
        if (Db::name('users')->where("login_ip",$ip)->count()>5) {
                return json(array('code'=>400,'msg'=>'当前IP已经注册了5个账号,不可再注册了！','data'=>''));
            }
        // 昵称长度限制
        if (mb_strlen($da['nickname']) > 20) {
            return json(array('code'=>400,'msg'=>'昵称不能超过20个字符','data'=>''));
        } 
        //ThinkPHP 验证器:nickname包括必填、长度限制、字符类型（中文、字母、数字）、禁止空白字符、敏感词过滤以及唯一性检查
        try {
            validate(User::class)->check($da);
        } catch (\think\exception\ValidateException $e) {
            return json(['code' => 400, 'msg' => $e->getMessage()]);
        }       
        // 验证密码（如果提供了密码）
        $password = input('password','','trim');
        if (!empty($password)) {
            if (strlen($da['password']) < 6) {
                return json(array('code'=>400,'msg'=>'密码长度不能少于6位','data'=>''));
            }
        }
        //注册账号处理
        $user = input('username','','htmlspecialchars'); 
        if ($password) {
            $data['password'] = password($password);
        }
        $data['sex'] = input('sex',0,'intval');
        if($data['sex']==1){
            $data['head_img'] = '/static/xfadmin/img/1.png';
        }else{
            $data['head_img'] = '/static/xfadmin/img/2.gif';
        }        
        $data['phone'] = $da['phone'];
        $data['name'] = $da['nickname'];
        $data['username'] = $da['phone'];        
        $data['device_id'] = $da['deviceId'];        
        $data['lang'] = input('lang',0,'trim');
        $data['login_ip'] = $ip;
        $data['birthday'] = date('Y-m-d',time());
        if (Db::name('users')->where("phone",$da['phone'])->count()) {
            return json(array('code'=>400,'msg'=>'手机号已经注册过了！','data'=>''));
        }
        $data['reg_time'] = time();
        $uid = Db::name('users')->insertGetId($data);
        if($uid>0){
            // 生成 Access Token（2小时有效）
                $access = [
                    'userId' => $uid,
                    'phone' => $data['phone'],
                    'nickname' => $data['name'],
                    'type' => 'access',
                    'iat' => time(),
                    'exp' => time() + $this->ACCESS_TOKEN_EXPIRES_IN  // 24小时后过期
                ];
                $accessToken = JWT::encode($access, $this->JWT_SECRET, "HS256");  //生成了 token
                // 生成 Refresh Token（30天有效）
                $refresh = [
                    'userId' => $uid,
                    'phone' => $data['phone'],
                    'nickname' => $data['name'],
                    'type' => 'refresh',
                    'iat' => time(),
                    'exp' => time() + $this->REFRESH_TOKEN_EXPIRES_IN  // 30天后过期
                ];
                $refreshToken = JWT::encode($refresh, $this->JWT_SECRET, "HS256");  //生成了 token
                return json([
                'code' => 200,
                'msg' => '注册成功',
                'data' => [
                    'userId' => $uid,
                    'phone' => $da['phone'],
                    'nickname' => $data['name'],
                    'hasPassword' => '',
                    'accessToken' => $accessToken,
                    'refreshToken' => $refreshToken,
                    'tokenType' => 'Bearer'
                    ]
                ]);
        }else{
            return json(array('code'=>500,'msg'=>'注册失败','data'=>''));
        }
         
        
    }
/**
 * Token 刷新接口
 * POST /api/xiaofeng/refresh
 * 使用 Refresh Token 获取新的 Access Token
 */
    public function refresh(){
        $data = TrimArray(input());
        // 验证 Refresh Token
        if (empty($data['refreshToken'])) {
            return json(array('code'=>400,'msg'=>'Refresh Token 不能为空','data'=>''));
        }        
        try {
                $decoded = JWT::decode($data['refreshToken'], new Key($this->JWT_SECRET, 'HS256'));
                // 生成 Access Token（2小时有效）
                $access = [
                    'userId' => $decoded->userId,
                    'phone' => $decoded->phone,
                    'nickname' => $decoded->nickname,
                    'type' => 'access',
                    'iat' => time(),
                    'exp' => time() + $this->ACCESS_TOKEN_EXPIRES_IN  // 24小时后过期
                ];
                $newAccessToken = JWT::encode($access, $this->JWT_SECRET, "HS256");  //生成了 token
                // 验证数据库中的状态...
                return json([
                        'code' => 200,
                        'msg' => '刷新成功',
                        'data' => [
                            'accessToken' => $newAccessToken,
                            'refreshToken' => $data['refreshToken'],
                            'tokenType' => 'Bearer'
                        ]
                    ]);
                } catch (ExpiredException $e) {
                    http_response_code(401);
                    return json([
                        'error' => 'refresh_token_expired',
                        'msg' => 'Refresh token expired, please login again'
                    ]);
                    exit;
                }catch (Exception $e) {
                // 处理其他验证异常
                http_response_code(401);
                return json(['error' => 'invalid_token', 'msg' => '无效的 token']);
                exit;
            }
    }


   
   
  
}
