<?php

namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\facade\View;
use think\facade\Db;
use think\facade\Request;
use fast\AjaxPage;
use fast\Tree;
use think\facade\Config;
class Member extends BaseController
{


   //负责数据查询---显示会员列表
    public function index(){
        $usergroup = Db::name('auth_group')->field('id,title')->select();
        View::assign('usergroup', $usergroup);
        return View::fetch();
    }

   //用户列表
    public function ulist(){
        $uid = input('post.uid');
        $gid = input('post.group');       
        $field = input('post.field','');
        $keyword = input('post.keyword','');
        $order = input('post.order',0);
        $where = null;     
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
        if($gid>0){
            if($keyword <> ''){
                $where = $where." and a.group_id=$gid";
            }else{
                $where = "a.group_id=$gid";
            }  
            $count = Db::name('users')->alias('u')->join('auth_group_access a', 'u.id=a.uid')->field('a.group_id,u.*')->where($where)->count();
            $Page = new AjaxPage($count,12);
            $show = $Page->show();
            $list = Db::name('users')->alias('u')->order($order)->join('auth_group_access a', 'u.id=a.uid')->field('a.group_id,u.*')->where($where)->limit($Page->firstRow,$Page->listRows)->select();
            
        }else{            
            $count = Db::name('users')->where($where)->count();
            $page = new AjaxPage($count,12);
            $show = $page->show();
            $list = Db::name('users')->order($order)->where($where)->limit($page->firstRow,$page->listRows)->select();
        }       
        View::assign('list',$list);
        View::assign('show',$show);
        View::assign('count',$count);
        return View::fetch();
    }
    //会员禁用和开启
    public function userstop(){
        if (!Request::isAjax()) {
             return redirect('index');
        }
            $User = Db::name("users");
            $vo = input('get.v');
            $id = input('get.id');
        if($vo == 1){
            //要修改stop值,0正常,1禁用
            $data['stop'] = 0;
            $User->where(array('id'=>$id))->save($data); // 根据条件更新记录    
        }elseif($vo == 0){
            $data['stop'] = 1;
            $User->where(array('id'=>$id))->save($data); // 根据条件更新记录        
        }
            
    }
    //在职状态修改
    public function userstatus(){
        if (!Request::isAjax()) {            
            return redirect('index');           
        }
            $User = Db::name("users");
            $vo = input('get.v');
            $id = input('get.id');
        if($vo == 1){
            //要修改stop值,0正常,1禁用
            $data['status'] = 0;
            $User->where(array('id'=>$id))->save($data); // 根据条件更新记录    
        }elseif($vo == 0){
            $data['status'] = 1;
            $User->where(array('id'=>$id))->save($data); // 根据条件更新记录        
        }
            
        }
    //执行修改用户密码(返回到当前分页)
    public function pass(){
        if (!Request::isAjax()) {
                 return redirect('index');
                }
        $id = trim(input('post.uid'));
        $p1 = trim(input('post.password'));
        $p2 = trim(input('post.repassword'));
        $name = trim(input('post.username'));
        $user = Db::name('users');
        if($p1!=$p2){
            $data = array('code'=>0,'msg'=>'两次密码不同');
            return json($data);
            
        }
        if(strlen($p2)<5 || strlen($p2)>16){
            $data = array('code'=>1,'msg'=>'密码必须在5-16位');
            return json($data);
        }      
            $data['password'] = password($p2);
            $user->where(array('id'=>$id))->save($data);
            userLog('后台修改用户密码,用户名:'.$name,5); 
            $data = array('code'=>2,'msg'=>'修改成功,请手动关闭窗口');
            return json($data);
        
        
    }
    public function edit(){
        $uid = input('uid');
        if ($uid) {
            $member = Db::table('xf_users')->alias('a')->field("a.*,w.group_id")->join('auth_group_access w', 'a.id = w.uid')->where('a.id',$uid)->find();
            //$member = Db::table('xf_users')->alias('a')->join('auth_group_access w','a.id=w.uid')->where('a.id',$uid)->find();
            //dump(Db::table('xf_users')->getLastSql());exit;
        } else {
            $this->error('参数错误！');
        }
        
        $usergroup = Db::name('auth_group')->field('id,title')->select();
        $usergroup_access = Db::name('auth_group_access')->where(array('uid'=>$uid))->value('group_id');
        $rule = Db::name('company')->field('id,pid,cname')->where(array('status'=>1,'pid'=>0))->order('o asc')->select();
        $gidname = Db::name('company')->where(array('id'=>$member['gid'],'status'=>1))->value('cname');
        //$mid = explode(',',$member['mid']);
        //$gid = explode(',',$member['gid']);
        View::assign('gidname', $gidname);
        View::assign('mid', $member['mid']);
        View::assign('gid', $member['gid']);
        View::assign('rule', $rule);
        View::assign('usergroup', $usergroup);
        View::assign('usergroup_access', $usergroup_access);
        View::assign('member', $member);
        return View::fetch('form');
    }
    //查看
    public function show(){
        $uid = input('uid');
        if ($uid) {
            $member = Db::table('xf_users')->alias('a')->join('auth_group_access w','a.id=w.uid')->where('a.id',$uid)->find();
        } else {
            $this->error('参数错误！');
        }
        $usergroup = Db::name('auth_group')->field('id,title')->select();
        $usergroup_access = Db::name('auth_group_access')->where(array('uid'=>$uid))->value('group_id',true);
        $rule = Db::name('company')->field('id,pid,cname')->where(array('status'=>1,'pid'=>0))->order('o asc')->select();
        $gidname = Db::name('company')->where(array('id'=>$member['gid'],'status'=>1))->value('cname');;
        //$mid = explode(',',$member['mid']);
        //$gid = explode(',',$member['gid']);
        View::assign('gidname', $gidname);
        View::assign('mid', $member['mid']);
        View::assign('gid', $member['gid']);
        View::assign('rule', $rule);
        View::assign('usergroup', $usergroup);
        View::assign('usergroup_access', $usergroup_access);
        View::assign('member', $member);
        return View::fetch();
    }
    //修改用户信息
    public function update($ajax = ''){
        if ($ajax == 'yes') {
            $uid = input('get.uid', 0, 'intval');
            $gid = input('get.gid', 0, 'intval');
            Db::name('auth_group_access')->where("uid",$uid)->save(array('group_id' => $gid));
            die('1');
        }
        $d = input('post.');
        // foreach ($d['company'] as $key => $value) {
        //    $company .=$value.',';
        // }
        // foreach ($d['rules'] as $key => $value) {
        //    $rules .=$value.',';
        // }
        $data['mid'] = $d['mid'];
        $data['gid'] = $d['gid'];
        
        $uid = input('uid');
        $user = input('username','','htmlspecialchars');
        $group_id = input('post.group_ids');
        if (!$group_id) {
            $this->error('请选择用户组！');
        }
        $password = input('username','','trim');
        if ($password) {
            $data['password'] = password($password);
        }
        //$head = input('post.head', '', 'strip_tags');
        $data['sex'] = input('sex',0,'intval');
        if($data['sex']==1){
            $data['head_img'] = '/static/xfadmin/img/1.png';
        }else{
            $data['head_img'] = '/static/xfadmin/img/2.gif';
        }
        
        //$data['birthday'] = isset($_POST['birthday']) ? strtotime($_POST['birthday']) : 0;
        $data['birthday'] = input('birthday',0);
        $data['phone'] = input('phone','','trim');
        $data['telphone'] = input('telphone','','trim');
        $data['post'] = input('post','','trim');
        $data['name'] = input('name','','trim');
        $data['ename'] = input('ename','','trim');
        $data['email'] = input('email','','trim');
        $data['lang'] = input('lang',0,'trim');
        if (!$uid) {
            if ($user == '') {
                $this->error('用户名称不能为空！');
            }
            if (!$password) {
                $this->error('用户密码不能为空！');
            }
            if (Db::name('users')->where("username",$user)->count()) {
                $this->error('用户名已被占用！');
            }
            $data['username'] = trim($user);
            $data['reg_time'] = time();
            $uid = Db::name('users')->insert($data);
            foreach ($group_id as $k => $v) {
                $group=array(
                    'uid'=>$uid,
                    'group_id'=>$v
                    );
                Db::name('AuthGroupAccess')->insert($group);
            }
            //addlog('新增会员，会员UID：' . $uid);
        } else {
         
            // 修改权限,先删除
            Db::name('AuthGroupAccess')->where(array('uid'=>$uid))->delete();
            foreach ($group_id as $k => $v) {
                $group=array(
                    'uid'=>$uid,
                    'group_id'=>$v
                    );
                Db::name('AuthGroupAccess')->insert($group);
            }

           
           $s= Db::name('users')->where(array('id'=>$uid))->save($data);
        }
        $this->success('操作成功！','index');
    }


    public function add(){

        $usergroup = Db::name('auth_group')->field('id,title')->select();
        $rule = Db::name('company')->field('id,pid,cname')->where(array('status'=>1,'pid'=>0))->order('o asc')->select();
        View::assign('rule', $rule);
        View::assign('usergroup', $usergroup);
        return View::fetch('form');
    }
    //Ajax获取部门信息
    public function gidinfo(){

        if (!Request::isAjax()) {
                 return redirect('index');
                }
        // $mid = I('post.mid');
        // $rule = M('company')->field('id,pid,cname')->where(array('status'=>1,'pid'=>$mid))->order('o asc')->select();
        
        // $this->ajaxReturn($rule);exit;
        $mid = input('post.mid');
        $pid = input('post.gid');
        $rule = Db::name('company')->field('id,pid,cname,ename')->where(array('status'=>1,'islink'=>1))->order('o asc')->select();
        $tree = new Tree($rule);
       
        //判断用户语言
        if(session('user.lang')==1){
              $str = "<option value=\$id \$selected>\$spacer\$ename</option>";
        }else{
              $str = "<option value=\$id \$selected>\$spacer\$cname</option>";
        }
        $category = $tree->get_tree($mid, $str, $pid);
        if($category){
            $data = '<select id="gid" name="gid" class="form-control"><option value="">选择</option>'.$category.'</select>';
        }else{
            $data = '<select id="gid" name="gid" class="form-control"><option value="">空</option></select>';
        }
        
        //dump($category);exit;
        return json($data);
    }

    
}
