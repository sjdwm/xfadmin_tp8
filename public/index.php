<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

require __DIR__ . '/../vendor/autoload.php';


// 执行HTTP应用并响应
$http = (new App())->http;
//隐藏默认应用名称home,以home为首页
$_amain = 'home';
$_aother = 'admin|api|Admin|ADMIN';//这里是除了home以外的所有其他应用(区分大小写)
if(preg_match('/^\/('.$_aother.')\/?/',$_SERVER['REQUEST_URI'])){
    $response = $http->run();
}else{
    $response = $http->name($_amain)->run();
}
//$response = $http->run();
// 判断是否安装
if (!is_file(app()->getRootPath() . 'public/Install/install.lock')) {
    header("location:/Install/index.php");
    exit;
}
$response->send();

$http->end($response);
