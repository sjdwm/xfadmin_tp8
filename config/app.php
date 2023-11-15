<?php
// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

return [
    // 应用地址
    'app_host'         => env('APP_HOST', ''),
    // 应用的命名空间
    'app_namespace'    => '',
    // 是否启用路由
    'with_route'       => true,
    // 默认应用
    'default_app'      => 'home',
    // 默认时区
    'default_timezone' => 'Asia/Shanghai',

    // 应用映射（自动多应用模式有效）
    'app_map'          => [],
    // 域名绑定（自动多应用模式有效）
    'domain_bind'      => [],
    // 禁止URL访问的应用列表（自动多应用模式有效）
    'deny_app_list'    => ['common'],

    // 异常页面的模板文件
    'exception_tmpl'   => app()->getThinkPath() . 'tpl/think_exception.tpl',
    // 默认跳转页面对应的模板文件【新增】
    /*'dispatch_success_tmpl' => app()->getRootPath() . '/public/static/tpl/dispatch_jump.tpl',
    'dispatch_error_tmpl'  => app()->getRootPath() . '/public/static/tpl/dispatch_jump.tpl',*/
    'dispatch_success_tmpl' => app()->getRootPath() . 'app\admin\view\Public\jump.html',
    'dispatch_error_tmpl'  => app()->getRootPath() . 'app\admin\view\Public\jump.html',

    // 错误显示信息,非调试模式有效
    'error_message'    => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'   => true,
    //设置cookie加密密钥
    'cookie_salt' => 'xiaofeng', 
    //是否开启缓存
    'cache'  =>[
        'type' =>'File',
        //全局缓存有效期(秒)
        'expire' => 0,
        //缓存前缀
        'prefix' => '',
        //缓存目录(File缓存方式有效)
        'path' => '',
    ],
    //Mysql备份配置
    'DB_PATH_NAME' => 'db',        //备份目录名称,主要是为了创建备份目录
    'DB_PATH' => app()->getRootPath() . 'public\db',     //数据库备份路径必须以 / 结尾；
    'DB_PART' => '20971520',  //该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M
    'DB_COMPRESS' => '1',         //压缩备份文件需要PHP环境支持gzopen,gzwrite函数        0:不压缩 1:启用压缩
    'DB_LEVEL' => '9',         //压缩级别   1:普通   4:一般   9:最高
];
