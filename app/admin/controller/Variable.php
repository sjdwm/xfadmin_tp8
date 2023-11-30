<?php

namespace app\admin\controller;

use app\admin\controller\ComController;
use think\facade\View;
use think\facade\Db;
use fast\Page;
class Variable extends ComController
{

//自定义变量控制器
   public function index()
    {
        $vars = Db::name('setting')->where('type=1')->select();
        View::assign('vars', $vars);
        return View::fetch();
    }

    public function add()
    {

        return View::fetch('form');
        
    }

    public function edit($k = null)
    {

        $var = Db::name('setting')->where("k='$k'")->find();
        if (!$var) {
            $this->error('参数错误！');
        }
        View::assign('var', $var);
        return View::fetch('form');
    }

    public function del()
    {

        $k = input('get.k');
        if ($k <> '') {
            if (Db::name('setting')->where("type=1 and k='{$k}'")->delete()) {
                //addlog('删除自定义变量，ID：' . $k);
                $this->success('恭喜，删除成功！');
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }
    }

    public function update()
    {
        $model = Db::name('setting');
        $data['k'] = input('k');//用K做字段有问题
        $varname = input('varname');
        if ($data['k'] == '') {
            $this->error('变量名不能为空。');
        }
        if ($model->where("k='{$data['k']}'")->count() && $varname == '') {
            $this->error('变量名称已经存在。');
        }
        
        $data['v'] = input('v');
        $data['name'] = input('name');
        $data['type'] = 1;//自定义
        if ($varname == '') {
            $model->insert($data);
            addlog('新增自定义变量：' . $data['k']);
        } else {
            //dump($data);exit;
            //$data = "['k' => '111', 'v' => '222','name' => '3333', 'type' => 1, 'k' => 1]";//echo $data;exit;
            //$ss=Db::execute("UPDATE `xf_setting`  SET `k` = '{$data['k']}' , `v` = '{$data['v']}' , `name` = '{$data['name']}' , `type` = 1  WHERE  ( k='$varname' )");
            $ss=Db::execute("UPDATE `xf_setting`  SET `k` = ? , `v` = ? , `name` = ? , `type` = ?  WHERE   k=?",[$data['k'],$data['v'],$data['name'],$data['type'],$varname]);
            //dump($model->getLastSql());exit;
            //addlog('新增自定义变量：' . $data['k']);
        }

        $this->success('恭喜，操作成功！', url('index'));
    }
}
