<?php
namespace app\home\controller;

use app\home\controller\ComController;
use think\facade\Request;
use think\facade\Config;
use think\facade\View;
use think\facade\Db;
use fast\AjaxPage;
class User extends ComController
{
    
    public function index(){
        $user = Db::name('users')->where(array('id'=>session('user.id')))->find();
        View::assign('user', $user);
        return View::fetch();

    }  
    //用户信息显示
    public function info(){
        $user = Db::name('users')->where(array('id'=>session('user.id')))->find();//dump($user);exit;
        View::assign('user', $user);
        return View::fetch();

        
    } 
    //修改用户信息页面显示
    public function edit(){
        $user = Db::name('users')->where(array('id'=>session('user.id')))->find();//dump($user);exit;
        View::assign('user', $user);
        return View::fetch();
        
    }
    //修改密码页面显示
    public function passwd(){
        return View::fetch();
        
    }
    //执行修改密码
    public function editpwd(){
        if (!Request::isAjax()) {
             return redirect('index');
        }
        $id = session('user.id');
        $p1 = input('get.p1','','trim');
        $p2 = input('get.p2','','trim');
        $p3 = input('get.p3','','trim');
        if($p2!=$p3){
            return json(0);
        }
        if(strlen($p2)<6 || strlen($p2)>16){
            return json(3);
        }
        $userpwd = Db::name('users')->where(array('id'=>$id))->value('password');
        if ($userpwd != password($p1)) {
            return json(1);
        }else{
            $data['password'] = password($p2);
            Db::name('users')->where(array('id'=>$id))->save($data);
            userLog('用户修改密码,用户名:'.session('user.username'),5); 
            return json(2);
        }
        
    }
    //执行修改用户信息
    public function editinfo(){
        if (!Request::isAjax()) {
             return redirect('index');
        }
        $id = session('user.id');
        $c = input('get.cate','','trim');
        $d = input('get.d','','trim');        
        $i = Db::name('users')->where(array('id'=>$id))->value("$c");
        /*if($d==$i){

            exit;
        }*/
        if($c=='phone' || $c=='lang' || $c=='post' || $c=='birthday'){
            $data = array($c=>$d);
            $s = Db::name('users')->where(array('id'=>$id))->save($data); 
            if($s>0){
                /*if($c=='lang'){
                    session('user.lang',$d);
                }*/
                return json(array('code'=>1,'data'=>$d));
            }else{
                return json(array('code'=>2,'data'=>$d));
            }
        }else{
            return json(array('code'=>2,'data'=>$d));
        }
        
        
        
        
    }
    //集团通讯录
    public function phone(){
        $data = Db::name('mobile_phone_contacts')->field('dept')->Distinct(true)->orderRaw('CONVERT(dept USING gbk) asc')->where('dept','<>','')->select()->toArray();
        View::assign('data', $data);
        return View::fetch();
    }
    public function telphone(){
        $keyword = input('post.keyword','','htmlentities');
        $dept = input('post.dept','','htmlentities');
        //用户搜索
        $where = '';
        if($dept!='' and $keyword!=''){
            $where = "dept='$dept' and name like '%$keyword%'"; 
        }else{


         if ($keyword <> '') {
                $where = "name like '%$keyword%'"; 
            }
            if($dept!=''){
                $where = "dept='$dept'"; 
            }
        }
        $count = Db::name('mobile_phone_contacts')->where($where)->count();// 查询满足要求的总记录数
        //echo  Db::name("users")->getLastSql();exit;
        $Page = new AjaxPage($count,15);       
        $show = $Page->show();       
        $list = Db::name('mobile_phone_contacts')->where($where)->limit($Page->firstRow,$Page->listRows)->select()->toArray();
        View::assign('list',$list);
        View::assign('show',$show);
        return View::fetch();
    }
   //用户头像上传
    public function upimg(){
        $mimes = array(
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/pjpeg',
            'image/gif',
            'image/bmp',
            'image/x-png'
        );
        $files = request()->file();
        // 上传到本地服务器
        //$savename = \think\facade\Filesystem::disk('public')->putFile( '', $files['file']);
        try {
            $s = validate(['image'=>"fileSize:20480|fileExt:jpg,jpeg,ping,gif,bmp|image:2000,2000|fileMime:{$mimes}"])
            ->check($files);            
             $savename = \think\facade\Filesystem::disk('img_user')->putFile( '', $files['file'],'uniqid');//uniqid不生成日期目录            
            // 上传成功
            $src = config('filesystem.disks.userimg.url').'/'.str_replace('\\','/',$savename);
            $id = session('user.id');
            $i = Db::name('users')->where(array('id'=>$id))->value('head_img');
            if($i!='/Public/img/2.gif' && $i!='/Public/img/1.png'){
                @unlink('.'.$i);//删除之前的头像
            }            
            $src = config('filesystem.disks.img_user.url').'/'.str_replace('\\','/',$savename);
            $user = Db::name('users')->where(array('id'=>$id))->save(array('head_img'=>$src)); 
            $res = array('code'=>0,'msg'=>'上传成功','data'=>array('src'=>$src));
            return json($res);
        } catch (\think\exception\ValidateException $e) {
           //echo $e->getMessage();上传错误提示错误信息
            $res = array('code'=>1,'msg'=>$e->getMessage(),'data'=>array('src'=>''));
            return json($res);
        }
       
        

    }
    
}
