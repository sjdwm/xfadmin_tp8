<?php
/**
 *
 * 版权所有：小风博客<www.hotxf.com>
 * 作    者：XiaoFeng<admin@hotxf.com>
 * 日    期：2023-11-10 
 * 版    本：1.0.0
 * 功能说明：后台日志控制器。
 *
 **/
namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\facade\View;
use think\facade\Db;
use fast\Page;
use fast\AjaxPage;
use fast\IpLocation;
use think\facade\Request;
class Logs extends BaseController
{


   //系统日志
    public function systemlog(){
      
        $log = Db::name('user_log');
        //$log->where("t < $t")->delete();//删除60天前的日志
        $count = $log->count();
        $page = new Page($count,12);
        $data = $log->limit($page->firstRow , $page->listRows)->select();
        $show = $page->show();
        View::assign('page', $show);
        //View::assign('firstRow',$page->firstRow);
        View::assign('list',$data); 
        return View::fetch();
    }
    public function user_log(){

        return View::fetch();
    }
    /*
     * 用户日志
     */
    public function user_log_list(){
        $log = Db::name("user_log");
        $rank['rank'] = input('rank','0');
        if($rank['rank'] == '0'){unset($rank);}
        $count = $log->where($rank)->count();
        $Page = new AjaxPage($count,16);     
        $show = $Page->show();   
        $list = $log->where($rank)->order('id desc')->limit($Page->firstRow,$Page->listRows)->select()->toArray();        
        View::assign('list',$list);
        View::assign('show',$show);
        return View::fetch();
    }
    public function login_log(){

        return View::fetch();
    }
    /*
     * 访客日志
     */
    public function login_log_list(){
        $Ip = new IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
        $log = Db::name("user_login");
        $rank['rank'] = input('rank','0');
        if($rank['rank'] == '0'){unset($rank);}
        $count = $log->where($rank)->count();
        $Page = new AjaxPage($count,16);
        $show = $Page->show();
        $list = $log->where($rank)->order('id desc')->limit($Page->firstRow,$Page->listRows)->select()->toArray();
        //dump($Ip->getlocation('137.59.100.132'));exit;
        foreach ($list as $k => $v) {
            $list[$k]['weizhi']=$Ip->getlocation($v['ip']);
        }//dump($list);exit;
        View::assign('list',$list);
        View::assign('show',$show);
        return View::fetch();
    }


    
}
