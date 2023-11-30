<?php

namespace app\admin\controller;

use app\admin\controller\ComController;
use think\facade\View;
use think\facade\Db;
use fast\Page;
use fast\Data;
use fast\Tree;
class Menu extends ComController
{


   public function index()
    {
        $model = Db::name('auth_rule');
        $count = $model->count();
        $list = $model->order('o asc')->where(array('cate'=>1))->select()->toArray();
        $list = Data::tree($list,'name','id','pid');
        //$list = $this->getMenu($list);
        $page = new Page($count, 8);
        View::assign('list', $list);

        return View::fetch();
    }

    public function del()
    {
        $ids = input('request.ids');
        $ids = isset($ids) ? $ids : false;
        if (!$ids) {
            $this->error('请勾选删除菜单！');
        }
        //uid为1的禁止删除
        if (is_array($ids)) {
            foreach ($ids as $k => $v) {
                $ids[$k] = intval($v);
            }
            if (!$ids) {
                $this->error('参数错误！');
                $uids = implode(',', $uids);
            }
        }

        $map['id'] = array('in', $ids);
        if (Db::name('auth_rule')->where($map)->delete()) {

            addlog('删除菜单ID：' . $ids);
            $this->success('恭喜，菜单删除成功！');
        } else {
            $this->error('参数错误！');
        }
    }

    public function edit($id = 0)
    {
        $id = intval($id);
        $currentmenu = Db::name('auth_rule')->where("id='$id'")->find();
        if (!$currentmenu) {
            $this->error('参数错误！');
        }

        $option = Db::name('auth_rule')->where(array('cate'=>1))->order('o ASC')->select()->toArray();
        $tree = new Tree($option);
        $str = "<option value=\$id \$selected>\$spacer\$title</option>"; //生成的形式
        $option = $tree->get_tree(0, $str, $currentmenu['pid']);
        //$option = $this->getMenu($option);
        View::assign('option', $option);
        View::assign('currentmenu', $currentmenu);
        return View::fetch('form');
    }

    public function update()
    {
        $model = Db::name('auth_rule');
        $id = input('post.id', '', 'intval');
        $data['pid'] = input('post.pid', '', 'intval');
        $data['title'] = input('post.title', '', 'strip_tags');
        $data['name'] = input('post.name', '', 'strip_tags');
        $data['icon'] = input('post.icon');
        $data['islink'] = input('post.islink', '', 'intval');
        $data['status'] = 1;
        $data['o'] = input('post.o', '', 'intval');
        $data['tips'] = input('post.tips');
        if ($id) {
            $model->where("id='{$id}'")->save($data);
           // addlog('编辑菜单，ID：' . $id);
        } else {
            $model->insert($data);
            //addlog('新增菜单，名称：' . $data['title']);
        }

        $this->success('操作成功！','index');
    }


    public function add()
    {

        $option = Db::name('auth_rule')->where(array('cate'=>1))->order('o ASC')->select()->toArray();
        $tree = new Tree($option);
        $str = "<option value=\$id \$selected>\$spacer\$title</option>"; //生成的形式
        $option = $tree->get_tree(0, $str, $currentmenu['pid']);
        //$option = $this->getMenu($option);
        View::assign('option', $option);
        return View::fetch('form');
    }

    
}
