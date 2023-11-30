<?php

namespace app\admin\controller;

use app\admin\controller\ComController;
use think\facade\View;
use think\facade\Db;
use fast\Tree;
use fast\Data;
use fast\Page;
use think\facade\Request;
class Group extends ComController
{


   public function index(){
        $group = Db::name('auth_group')->select();
        View::assign('list', $group);
        View::assign('nav', array('user', 'grouplist', 'grouplist'));//导航
        return View::fetch();
    }
    //用户组成员
    public function usergroup(){
        $gid = input('get.id');
        $group = input('post.group');
        $usergroup = Db::name('auth_group')->field('id,title')->select();
        if(empty($group)){
            $group = $gid;
        }
        View::assign('usergroup', $usergroup);

        //$gmodel = Db::name('auth_group_access');
        $uid = input('post.uid');        
        $field = input('field','');
        $keyword = input('keyword','','htmlentities');
        $order =input('post.order',0);//dump($order);exit;
        $where = "w.group_id=$group";     
        //用户搜索
         if ($keyword <> '') {
            if ($field == 'username') {
                $where .= " and username LIKE '%$keyword%'";
            }
            if ($field == 'phone') {
                $where .= " and phone LIKE '%$keyword%'";
            }
            if ($field == 'name') {
                $where .= " and name LIKE '%$keyword%'";
            }
            if ($field == 'email') {
                $where .= " and email LIKE '%$keyword%'";
            }
        }
        //排序规则
        $order = user_order($order);
        $count = Db::name('users')->alias('a')->join('auth_group_access w', 'a.id=w.uid')->where($where)->count(); 
        $Page = new Page($count,12);
        $list = Db::name('users')->alias('a')->join('auth_group_access w', 'a.id=w.uid')->order($order)->where($where)->limit($Page->firstRow,$Page->listRows)->select();
        $show = $Page->show();
        // foreach ($list as $key => $value) {
        //    $s=$gmodel->where(array('group_id'=>$group,'uid'=>$value['id']))->find();
        //    if(!$s>0){
        //     unset($list[$key]);
        //    }
        // }//dump($gid);exit;
        View::assign('list',$list);
        View::assign('show',$show);
        View::assign('group',$group);
        return View::fetch();
    }
    //添加用户到组
    public function adduser(){
        $gid = input('get.id');
        $group = input('post.group');        
        $usergroup = Db::name('auth_group')->field('id,title')->where(array('id'=>$gid))->find();
        View::assign('usergroup', $usergroup);
        $model = Db::name('users');
        $gmodel = Db::name('auth_group_access');
        $uid = input('post.uid');
       
        $field = input('field','');
        $keyword = input('keyword','','htmlentities');
        $order = input('post.order',0);//dump($order);exit;
        $where = "";     
        //用户搜索
         if ($keyword <> '') {
            if ($field == 'username') {
                $where = "username LIKE '%$keyword%'";
            }
            if ($field == 'phone') {
                $where = "phone LIKE '%$keyword%'";
            }
            if ($field == 'name') {
                $where = "name LIKE '%$keyword%'";
            }
            if ($field == 'email') {
                $where = "email LIKE '%$keyword%'";
            }
        }
     
        //排序规则
        $order = user_order($order);
        $count = Db::name('users')->where($where)->count();
        $Page = new Page($count,20);
        $show = $Page->show();
        $list = Db::name('users')->order($order)->where($where)->limit($Page->firstRow,$Page->listRows)->select();
        foreach ($list as $key => $value) {
           $s=$gmodel->where(array('group_id'=>$gid,'uid'=>$value['id']))->find();
           if($s>0){
            unset($list[$key]);
           }
        }//dump($list);exit;
        View::assign('list',$list);// 赋值数据集
        View::assign('show',$show);// 赋值分页输出
        View::assign('count',$count);// 赋值用户数量输出
        return View::fetch();
    }
    //添加选中的用户到用户组
    public function addusergroup(){
        if (!Request::isAjax()) {
             return redirect('index');
        }
        $uid = input('get.uid', 0);
        $gid = input('get.gid', 0);
        //$this->ajaxReturn($gid);exit;
        if($uid >= 1){
        $group=array(
                    'uid'=>$uid,
                    'group_id'=>$gid
                    );
        Db::name('AuthGroupAccess')->insert($group);
        //$this->ajaxReturn($group);exit;
        }
    }
    //删除选中的用户组
    public function delgroup(){
        if (!Request::isAjax()) {
             return redirect('index');
        }
        $uid = input('get.uid', 0);
        $gid = input('get.gid', 0);
        //$this->ajaxReturn($gid);exit;
        if($uid >= 1){        
        Db::name('AuthGroupAccess')->where(array('uid'=>$uid,'group_id'=>$gid))->delete();
        //$this->ajaxReturn($group);exit;
        }
    }
    //删除用户组
    public function del(){

        $ids = input('ids','false');
        if (is_array($ids)) {
            foreach ($ids as $k => $v) {
                $ids[$k] = intval($v);
            }
            //$ids = implode(',', $ids);
            $map['id'] = $ids;
            if (Db::name('auth_group')->where($map)->delete()) {
                addlog('删除用户组ID：' . $ids);
                $this->success('恭喜，用户组删除成功！');
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }
    }
    //用户组权限修改
    public function update(){
        $id = input('id',false,'intval');
        $data['title'] = input('title',false,'trim');
        $status = input('status','','intval');
        $rules = input('rules',0);
        if ($data['title']) {
            
            if ($status == 'on') {
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }
            //如果是超级管理员一直都是启用状态
            if ($id == 1) {
                $data['status'] = 1;
            }

            
            if (is_array($rules)) {
                foreach ($rules as $k => $v) {
                    $rules[$k] = intval($v);
                }
                $rules = implode(',', $rules);
            }
            $data['rules'] = $rules;
            if ($id) {
                $group = Db::name('auth_group')->where('id',$id)->save($data);
                if ($group) {
                    //addlog('编辑用户组，ID：' . $id . '，组名：' . $data['title']);
                    $this->success('恭喜，用户组修改成功！','index');
                    
                } else {
                    $this->success('未修改内容');
                }
            } else {
                Db::name('auth_group')->insert($data);
                //addlog('新增用户组，ID：' . $id . '，组名：' . $data['title']);
                $this->success('恭喜，新增用户组成功！');
                
            }
        } else {
            $this->success('用户组名称不能为空！');
        }
    }
    //用户组权限编辑
    public function edit(){

        $id = input('id',false,'intval');
        if (!$id) {
            $this->error('参数错误！');
        }
        $group = Db::name('auth_group')->where('id',$id)->find();
        if (!$group) {
            $this->error('参数错误！');
        }
        //获取所有启用的规则
        $rule = Db::name('auth_rule')->field('id,pid,title')->where(array('cate'=>1,'status'=>1))->order('o asc')->select();
        $hrule = Db::name('auth_rule')->field('id,pid,title')->where(array('cate'=>2,'status'=>1))->order('o asc')->select();
        $group['rules'] = explode(',', $group['rules']);
        $rule = Data::channelLevel($rule,0,'&nbsp;','id');
        $hrule = Data::channelLevel($hrule,0,'&nbsp;','id');
        //$rule = $this->getMenu($rule);
        View::assign('rule', $rule);
        View::assign('hrule', $hrule);
        View::assign('group', $group);
        View::assign('nav', array('user', 'grouplist', 'addgroup'));//导航
        return View::fetch('form');
    }
    //新增用户组
    public function add(){
        //获取所有启用的规则
        $rule = Db::name('auth_rule')->field('id,pid,title')->where(array('cate'=>1,'status'=>1))->order('o asc')->select();
        $hrule = Db::name('auth_rule')->field('id,pid,title')->where(array('cate'=>2,'status'=>1))->order('o asc')->select();
        $rule = Data::channelLevel($rule,0,'&nbsp;','id');
        $hrule = Data::channelLevel($hrule,0,'&nbsp;','id');
        View::assign('rule', $rule);
        View::assign('hrule', $hrule);
        View::assign('group', array('rules'=>array()));
        return View::fetch('form');
    }

    public function status(){

        $id = input('id');
        if (!$id) {
            $this->error('参数错误！');
        }
        if ($id == 1) {
            $this->error('此用户组不可变更状态！');
        }
        $group = Db::name('auth_group')->where('id',$id)->find();
        if (!$group) {
            $this->error('参数错误！');
        }
        $status = $group['status'];
        if ($status == 1) {
           $res = Db::name('auth_group')->where('id',$id)->save(array('status' => 0));
        }
        if ($status != 1 ) {
            $res = Db::name('auth_group')->where('id',$id)->save(array('status' => 1));
        }
        if ($res) {
            $this->success('恭喜，更新状态成功！');
        } else {
            $this->error('更新失败！');
        }
    }
    //用户权限
     public function auth(){
         
        $list = Db::name('auth_rule')->order('o asc')->where(array('cate'=>1))->select()->toArray();
        $list = Data::tree($list,'name','id','pid');
        //$list = $this->getMenu($list);
        //dump($list);exit;

        
        View::assign('list', $list);

        return View::fetch();
    }
    //用户权限下级菜单的显示与隐藏
    public function is_show(){
        $pid = input('get.pid');
       
        $id = Db::name('auth_rule')->field('id')->where(array('pid'=>$pid))->select();
        foreach ($id as $k => $v) {
            
            $zid = Db::name('auth_rule')->field('id')->where(array('pid'=>$v['id']))->select();
            foreach ($zid as $key => $value) {
                $id[] = $value;
                $zzid = Db::name('auth_rule')->field('id')->where(array('pid'=>$value['id']))->select();
                foreach ($zzid as $kk => $vv) {
                    $id[] = array('id'=>$vv['id']);
                }
            }
        }
        //dump($id);exit;
        return json($id);
    }
    //添加权限
    public function authadd(){
        $pid = input('get.pid',0);
        $option = Db::name('auth_rule')->order('o ASC')->field('id,pid,title')->where(array('cate'=>1))->select()->toArray();
        $tree = new Tree($option);
        $str = "<option value=\$id \$selected>\$spacer\$title</option>"; //生成的形式
        $category = $tree->get_tree(0, $str, $pid);
        //$option = $this->getMenu($option);
        View::assign('option', $category);
        return View::fetch();
    }
    //更新
    public function authupdate(){
        $id = input('post.id', '', 'intval');
        $data['pid'] = input('post.pid', '', 'intval');
        $data['title'] = input('post.title', '', 'strip_tags');
        $data['name'] = input('post.name', '', 'strip_tags');
        //$data['ename'] = input('post.ename', '', 'strip_tags');
        $data['icon'] = input('post.icon');
        $data['islink'] = input('post.islink', '', 'intval');
        $data['status'] = 1;
        $data['o'] = input('post.o', '', 'intval');
        $data['tips'] = input('post.tips');
        if ($id) {
            Db::name('auth_rule')->where('id',$id)->save($data);
            addlog('编辑菜单，ID：' . $id);
        } else {
            Db::name('auth_rule')->insert($data);
            addlog('新增菜单，名称：' . $data['title']);
        }

        $this->success('操作成功！','auth');
    }
    //修改
    public function authedit(){
        $id = input('get.id', '', 'intval');
        $currentmenu = Db::name('auth_rule')->where('id',$id)->find();
        if (!$currentmenu) {
            $this->error('参数错误！');
        }

        $option = Db::name('auth_rule')->order('o ASC')->select()->toArray();
        $tree = new Tree($option);
        $str = "<option value=\$id \$selected>\$spacer\$title</option>"; //生成的形式
        $option = $tree->get_tree(0, $str, $currentmenu['pid']);
        //$option = $this->getMenu($option);
        View::assign('option', $option);
        View::assign('currentmenu', $currentmenu);
        return View::fetch('authadd');
    }
    //后台用户权限删除
    public function htdel(){

        $ids = input('ids','false');
        if (is_array($ids)) {
            foreach ($ids as $k => $v) {
                $ids[$k] = intval($v);
            }
            $ids = implode(',', $ids);
            $map['id'] = array('in', $ids);
            if (Db::name('auth_rule')->where(array('pid'=>array('in',$ids)))->count()) {
                $this->error('存在子类，严禁删除!');//存在子类，严禁删除。
            } 
            if (Db::name('auth_rule')->where($map)->delete()) {
               
                $this->success('恭喜，删除成功！');
            } else {
                //dump(M()->_sql());exit;
                $this->error('参数错误！');
            }
        } else {
            $ids=input('get.ids');
            if (Db::name('auth_rule')->where(array('pid'=>$ids))->count()) {
                
                $this->error('存在子类，严禁删除!');//存在子类，严禁删除。
            } 
            if (Db::name('auth_rule')->where(array('id'=>$ids))->delete()) {
               
                $this->success('恭喜，删除成功！');
            } else {
                //dump(M()->_sql());exit;
                $this->error('参数错误111！');
            }
        }
    }
    //前台用户权限
     public function homeauth(){
        //$count = Db::name('auth_rule')->count();

        $list = Db::name('auth_rule')->order('o asc')->where(array('cate'=>2))->select()->toArray();
        $list = Data::tree($list,'name','id','pid');//dump($cate);exit;
        //$list = $this->getMenu($list);//dump($list);exit;

        //$page = new Page($count, 12);
        View::assign('list', $list);

        return View::fetch();
    }
    //添加前台用户权限
    public function homeauthadd(){
        $pid = input('pid',0,'intval');
        $option = Db::name('auth_rule')->order('o ASC')->field('id,pid,title')->where(array('cate'=>2))->select()->toArray();
        $tree = new Tree($option);
        $str = "<option value=\$id \$selected>\$spacer\$title</option>"; //生成的形式
        $category = $tree->get_tree(0, $str, $pid);//dump($category);exit;
        //$option = $this->getMenu($option);
        View::assign('option', $category);
        return View::fetch();
    }
    //前台用户权限更新
    public function homeauthupdate(){
        $id = input('post.id', '', 'intval');
        $data['pid'] = input('post.pid', '', 'intval');
        $data['title'] = input('post.title', '', 'strip_tags');
        //$data['ename'] = I('post.ename', '', 'strip_tags');
        $data['name'] = input('post.name', '', 'strip_tags');
        $data['url'] = input('post.url', '', 'strip_tags');
        //$data['icon'] = I('post.icon');
        $data['islink'] = input('post.islink', '', 'intval');
        $data['status'] = 1;
        $data['cate'] = 2;
        $data['o'] = input('post.o', '', 'intval');
        //$data['tips'] = I('post.tips');
        if ($id) {
            Db::name('auth_rule')->where('id',$id)->save($data);
            //addlog('编辑菜单，ID：' . $id);
        } else {
            Db::name('auth_rule')->insert($data);
            //addlog('新增菜单，名称：' . $data['title']);
        }

        $this->success('操作成功！','homeauth');
    }
    //前台用户权限修改
    public function homeauthedit(){
        $id = input('id', '0', 'intval');
        $currentmenu = Db::name('auth_rule')->where("id='$id'")->find();
        if (!$currentmenu) {
            $this->error('参数错误！');
        }

        $option = Db::name('auth_rule')->order('o ASC')->where(array('cate'=>2))->select()->toArray();
        $tree = new Tree($option);
        $str = "<option value=\$id \$selected>\$spacer\$title</option>"; //生成的形式
        $option = $tree->get_tree(0, $str, $currentmenu['pid']);
        //$option = $this->getMenu($option);
        View::assign('option', $option);
        View::assign('currentmenu', $currentmenu);
        return View::fetch('homeauthadd');
    }
    //删除前台权限
     public function homedel(){

        $ids = input('ids','false');
        if (is_array($ids)) {
            foreach ($ids as $k => $v) {
                $ids[$k] = intval($v);
            }
            $ids = implode(',', $ids);
            $map['id'] = array('in', $ids);
            if (Db::name('auth_rule')->where(array('pid'=>array('in',$ids)))->count()) {
                $this->error('存在子类，严禁删除!');//存在子类，严禁删除。
            } 

            if (Db::name('auth_rule')->where($map)->delete()) {
               
                $this->success('恭喜，删除成功！');
            } else {
                //dump(M()->_sql());exit;
                $this->error('参数错误！');
            }
        } else {
            $ids=input('get.ids');
            if (Db::name('auth_rule')->where(array('pid'=>$ids))->count()) {
                
                $this->error('存在子类，严禁删除!');//存在子类，严禁删除。
            } 
            
            if (Db::name('auth_rule')->where(array('id'=>$ids))->delete()) {
               
                $this->success('恭喜，删除成功！');
            } else {
                //dump(M()->_sql());exit;
                $this->error('参数错误！');
            }
        }
    }

    
}
