<?php

namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\facade\View;
use think\facade\Db;
class Setting extends BaseController
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
        $this->success('恭喜，网站配置成功！');
    }

    
}
