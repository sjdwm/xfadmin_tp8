<?php

namespace app\admin\controller;

use app\admin\controller\ComController;
use think\facade\View;
use think\facade\Db;
use think\facade\Cache;
class Setting extends ComController
{

//网站设置控制器
   public function setting()
    {

        $vars = Db::name('setting')->where('type=1')->select();
        View::assign('vars', $vars);

        return View::fetch();
    }

    public function update()
    {

        $data = input('post.');

        foreach ($data as $k => $v) {
            Db::name('setting')->where("k",$k)->update(array('v' => $v));
        }
        Cache::delete('all');
        config(setting(),'config');
        $this->success('恭喜，网站配置成功！');
    }

    
}
