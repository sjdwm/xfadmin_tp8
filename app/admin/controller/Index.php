<?php

namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\facade\View;
use think\facade\Db;
use fast\Page;
class Index extends BaseController
{

    public function index()
    {
        
        $model = Db::name('users');
        $mysql = Db::query("select VERSION() as mysql");
        $count = $model->count();
        //以下是统计近十五天内增加的用户数量
        $i =0;
        $arr =array();
        for($i;$i<15;$i++)
        {
            $j =$i - 1;
            $arr[$i] =$this -> day_add(time(),'-'.$i.' day','-'.$j.' day');
        }
        //留言条数
        View::assign('mysql', $mysql[0]['mysql']);
        View::assign('users',$count);
        View::assign('user_line',$arr);
        return View::fetch();

    }
    function day_add($time,$date,$mdate){
        if($date==$mdate){
            $mdate = '+1 day';
        }
        $id = session('user.id');
        $users =Db::name('user_log');
        $day =date("m-d",strtotime($date));
        $time1 =strtotime(date("Y-m-d",strtotime($date)));
        $time2 =strtotime(date("Y-m-d",strtotime($mdate)));
        //@rank=1(前台登录),@rank=3(后台登录)
        $num =$users -> where("'rank'='3' and time > '$time1' and time < '$time2' ")-> count();
        $result =array('day'=>$day,'num'=>$num);
        return $result;
    }
    
}
