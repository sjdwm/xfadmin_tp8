<?php

namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\facade\View;
use think\facade\Db;
use fast\Tree;
use fast\Data;
use think\facade\Request;
class Company extends BaseController
{


   public function index(){

        $list = Db::name('company')->order('o asc')->select()->toArray();
        $list = Data::tree($list,'cname','id','pid');

        View::assign('list', $list);

        return View::fetch();
    }
   //用户权限下级菜单的显示与隐藏
    public function is_show(){
        $pid = input('get.pid');
        $id = Db::name('company')->field('id')->where(array('mid'=>$pid))->select();
        foreach ($id as $k => $v) {
            
            $zid = Db::name('company')->field('id')->where(array('pid'=>$v['id']))->select();
            foreach ($zid as $key => $value) {
                $id[] = $value;
            }
        }
        //dump($id);exit;
        return json($id);
    }
    //添加权限
    public function add(){
        $act = input('get.act', null);
        if ($act == 'order') {
            $id = input('post.id', 0, 'intval');
            if (!$id) {
                die('0');
            }
            $o = input('post.o', 0, 'intval');
            Db::name('company')->where(array('id'=>$id))->save(array('o' => $o));
            //addlog('分类修改排序，ID：' . $id);
            die('1');
        }elseif ($act == 'email') {
            $id = input('post.id', 0, 'intval');
            if (!$id) {
                die('0');
            }
            $o = input('post.o','',FILTER_VALIDATE_EMAIL);
            Db::name('company')->where(array('id'=>$id))->save(array('email' => $o));
            die('1');
        }elseif ($act == 'bm') {
            $id = input('post.id', 0, 'intval');
            if (!$id) {
                die('0');
            }
            $o = input('post.o');
            Db::name('company')->where(array('id'=>$id))->save(array('bm' => $o));
            //return json(1);
            die('1');
        }
        $pid = input('pid',0,'intval');
        $option = Db::name('company')->order('o ASC')->field('id,pid,cname')->select()->toArray();
        $tree = new Tree($option);
        $str = "<option value=\$id \$selected>\$spacer\$cname</option>"; //生成的形式
        $category = $tree->get_tree(0, $str, $pid);
        //$option = $this->getMenu($option);
        View::assign('option', $category);
        return View::fetch();
    }
    //更新
    public function update(){
      
        $id = input('post.id', '', 'intval');
        $data['pid'] = input('post.pid', '', 'intval');
        $data['cname'] = input('post.cname', '', 'strip_tags');
        $data['ename'] = input('post.ename', '', 'strip_tags');
        $data['email'] = input('post.email');
        $data['address'] = input('post.address');
        $data['phone'] = input('post.phone');
        $data['islink'] = input('post.islink', '', 'intval');
        $data['status'] = 1;//公司状态
        $data['o'] = input('post.o', '', 'intval');
        $data['tips'] = input('post.tips');
        $data['time'] = time(); 
        $data['mid']= Db::name('company')->where(array('id'=>$data['pid']))->value('mid');
               
        if ($id) {
            Db::name('company')->where("id='{$id}'")->save($data);
        } else {
            
            Db::name('company')->insert($data);
        }

        $this->success('操作成功！','index');
    }
    //修改
    public function edit(){
        $id = input('get.id', '', 'intval');
        $currentmenu = Db::name('company')->where("id='$id'")->find();
        if (!$currentmenu) {
            $this->error('参数错误！');
        }

        $option = Db::name('company')->order('o ASC')->select()->toArray();
        $tree = new Tree($option);
        $str = "<option value=\$id \$selected>\$spacer\$cname</option>"; //生成的形式
        $option = $tree->get_tree(0, $str, $currentmenu['pid']);
        //$option = $this->getMenu($option);
        View::assign('option', $option);
        View::assign('currentmenu', $currentmenu);
        return View::fetch('add');
    }

    
}
