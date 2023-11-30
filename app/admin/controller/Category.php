<?php

namespace app\admin\controller;

use app\admin\controller\ComController;
use think\facade\View;
use think\facade\Db;
use fast\Tree;
use fast\Data;
use think\facade\Request;
class Category extends ComController
{


   public function index(){

        //修改3级不显示问题
        $data = Db::name('category')->field('id,pid,name,dir,url,o')->order('o asc')->select()->toArray();
        //$category = $this->getMenu($category);
        $category = Data::tree($data,'name','id','pid');//dump($category);exit;
        View::assign('category', $category);
        return View::fetch();
    }
    
    public function del(){

        $id = input('get.id', false, 'intval');
        if ($id) {
            $data['id'] = $id;
            if (Db::name('category')->where('pid',$id)->count()) {
                die('2');//存在子类，严禁删除。
            } else {
                Db::name('category')->where('id',$id)->delete();
                //addlog('删除分类，ID：' . $id);
            }
            die('1');
        } else {
            die('0');
        }

    }

    public function edit(){

        $id = input('get.id', false, 'intval');
        $currentcategory = Db::name('category')->where('id',$id)->find();
        View::assign('currentcategory', $currentcategory);

        $category = Db::name('category')->field('id,pid,name')->where("id <> {$id}")->order('o asc')->select()->toArray();
        $tree = new Tree($category);
        $str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
        $category = $tree->get_tree(0, $str, $currentcategory['pid']);

        View::assign('category', $category);
        return View::fetch('form');
    }

    public function add(){

        $pid = input('get.pid', 0, 'intval');
        $category = Db::name('category')->field('id,pid,name')->order('o asc')->select()->toArray();
        $tree = new Tree($category);
        $str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
        $category = $tree->get_tree(0, $str, $pid);

        View::assign('category', $category);
        return View::fetch('form');
    }

    public function update(){
        $act = input('act');
        if ($act == 'order') {
            $id = input('post.id', 0, 'intval');
            if (!$id) {
                die('0');
            }
            $o = input('post.o', 0, 'intval');
            Db::name('category')->where('id',$id)->save(array('o' => $o));
            //addlog('分类修改排序，ID：' . $id);
            die('1');
        }

        $id = input('post.id', false, 'intval');
        $data['type'] = input('post.type', 0, 'intval');
        $data['show'] = input('post.show', 1, 'intval');
        $data['pid'] = input('post.pid', 0, 'intval');
        $data['name'] = input('post.name');
        //$data['ename'] = input('post.ename');
        $data['dir'] = input('post.dir','',array('strip_tags','trim'));
        $data['seotitle'] = input('post.seotitle', '', 'htmlspecialchars');
        $data['keywords'] = input('post.keywords', '', 'htmlspecialchars');
        $data['description'] = input('post.description', '', 'htmlspecialchars');
        $data['content'] = input('post.content', false);
        $data['url'] = input('post.url');
        $data['cattemplate'] = input('post.cattemplate');
        $data['contemplate'] = input('post.contemplate');
        $data['o'] = input('post.o', 0, 'intval');
        if ($data['name'] == '') {
            $this->error('分类名称不能为空！');
        }
        if ($id) {
            if (Db::name('category')->where('id',$id)->save($data)) {
                //addlog('文章分类修改，ID：' . $id . '，名称：' . $data['name']);
                $this->success('恭喜，分类修改成功！','index');
                die(0);
            }
        } else {
            $id = Db::name('category')->insert($data);
            if ($id) {
                //addlog('新增分类，ID：' . $id . '，名称：' . $data['name']);
                $this->success('恭喜，新增分类成功！', 'index');
                die(0);
            }
        }
        $this->success('恭喜，操作成功！');
    }

    
}
