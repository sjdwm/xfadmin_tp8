<?php
/**
 *
 * 版权所有：小风博客<www.hotxf.com>
 * 作    者：XiaoFeng<admin@hotxf.com>
 * 日    期：2018-05-28 09:15:08
 * 版    本：1.0.0
 * 功能说明：行事历控制器。
 *
 **/
namespace app\home\controller;

use app\home\controller\ComController;
use think\facade\Config;
use think\facade\Request;
use think\facade\View;
use think\facade\Db;
use fast\Page;
header("content-type:text/html;charset=utf-8");
    
class Calendar extends ComController{
    /*public $auth=0;
    public function _initialize(){
        //有添加修改权限的才显示添加修改菜单
        $auth=new \Think\Auth();
        $rule_name= 'Calendar/dorili';
        $result=$auth->check($rule_name,session('user.id'));
        if($result){
           //$this->assign('auth',1);
           $this->auth=1;
        } //dump($result);exit;
        parent::_initialize();//执行父类的内容
    }*/
    //行事历
    public function index(){
        
           View::assign('auth',1);
           View::assign('data',array('id'=>1));
           return View::fetch('dfsx');

    }
    //这个暂时不用
    public function schedule(){
        $data = M('company')->where(array('id'=>1))->find();
        //判断用户权限,用户所在的公司只能添加自己公司的信息
        $mid = session('user.mid');
        if(($mid==1 && $this->auth==1) || ($mid==20 && $this->auth==1) || in_array(session('user.id'),array(1,2,3))){
            $this->assign('auth',1);
        }
        //改下颜色
        // $model = M('calendar');
        // $colors = array('#00FFFF','#7FFFD4','#F5F5DC','#90EE90','#E6E6FA','#FFF0F5','#ADFF2F','#EDCBD3','#E0FFFF','#FAFAD2','#90EE90','#FFB6C1','#FFFFE0','#FAF0E6','#FFE4E1','#FFCCCC','#FFFFCC','#FFFF99','#FAC9E8','#CCFF99','#CCFFFF','#FF99FF','#99FF99','#FFCC66','#FFCC66','#CCFFCC');
        
        
        // $d = $model->where(array('type'=>1))->select();
        // foreach ($d as $k => $v) {
        //     $key = array_rand($colors);
        //     $color = $colors[$key];
        //     $model->data(array('color'=>$color))->where(array('id'=>$v['id']))->save();
        // }
        $this->assign('data',$data);
        $this->display();
    }
    public function shanghai(){
        $data = M('company')->where(array('id'=>1))->find();
        //判断用户权限,用户所在的公司只能添加自己公司的信息
        $mid = session('user.mid');
        if($mid==1 && $this->auth==1 || in_array(session('user.id'),array(1,2,3))){
            View::assign('auth',1);
        }
        View::assign('data',$data);
        return View::fetch();
    }
    
    
    //用户提交日历
    public function dorili(){
        if (!Request::isAjax()) {
             return redirect('index');
        }
        $action = input('get.action');
        if($action=='add'){
            $events = stripslashes(input('post.event','','trim'));//事件内容
            $events=strip_tags($events); //过滤HTML标签，并转义特殊字符

            $isallday = input('post.isallday');//是否是全天事件
            $isend = input('post.isend');//是否有结束时间

            $startdate = input('post.startdate','','trim');//开始日期
            $enddate = input('post.enddate','','trim');//结束日期

            $s_time = input('post.s_hour').':'.input('post.s_minute').':00';//开始时间
            $e_time = input('post.e_hour').':'.input('post.e_minute').':00';//结束时间

            if($isallday==1 && $isend==1){
                $starttime = strtotime($startdate);//00:00开始
                if($startdate !=$enddate){
                    $endtime = strtotime($enddate);//全天+9小时
                }else{
                    $endtime = 0;
                }                
                
            }elseif($isallday==1 && $isend==""){
                $starttime = strtotime($startdate);//00:00开始
                $endtime = 0;
            }elseif($isallday=="" && $isend==1){
                $starttime = strtotime($startdate.' '.$s_time);
                $endtime = strtotime($enddate.' '.$e_time);
            }else{
                $starttime = strtotime($startdate.' '.$s_time);
                $endtime = 0;
            }
            //echo $startdate.' '.$s_time;exit;
            //$colors = array('#00FFFF','#7FFFD4','#F5F5DC','#90EE90','#E6E6FA','#FFF0F5','#ADFF2F','#EDCBD3','#E0FFFF','#FAFAD2','#90EE90','#FFB6C1','#FFFFE0','#FAF0E6','#FFE4E1','#FFCCCC','#FFFFCC','#FFFF99','#FAC9E8','#CCFF99','#CCFFFF','#FF99FF','#99FF99','#FFCC66','#FFCC66','#CCFFCC');
            //$key = array_rand($colors);
            //$color = $colors[$key];
            //$color = randrgb();
            $color = input('post.color')==''?randcolor():input('post.color');
            $isallday = $isallday?1:0;
            $cid = input('post.cid');
            $type = input('post.type',0);
            //判断用户权限,用户所在的公司只能添加自己公司的信息,排除管理员1,2,3,9 mona.lv
            $ucid = session('user.mid');
            if(($ucid != $cid) and !in_array(session('user.id'),array(1,2,3))){
                echo '没有权限,写入失败！';exit;
            }
            $model = Db::name('calendar');
            $cal = $model->insert(array('cid'=>input('post.cid'),'uid'=>session('user.id'),'type'=>$type,'title'=>$events,'start'=>$starttime,'end'=>$endtime,'allday'=>$isallday,'color'=>$color,'time'=>time()));
            
            if($cal>0){
                echo '1';
            }else{
                echo '写入失败！';
            }
        }elseif($action=="edit"){
                $id =input('post.id','','intval');
                if($id==0){
                    echo '事件不存在！';
                    exit;   
                }
                $events = stripslashes(input('post.event','','trim'));//事件内容
                $events=strip_tags($events); //过滤HTML标签，并转义特殊字符

                $isallday = input('post.isallday');//是否是全天事件
                $isend = input('post.isend');//是否有结束时间

                $startdate = input('post.startdate','','trim');//开始日期
                $enddate = input('post.enddate','','trim');//结束日期

                $s_time = input('post.s_hour').':'.input('post.s_minute').':00';//开始时间
                $e_time = input('post.e_hour').':'.input('post.e_minute').':00';//结束时间

                if($isallday==1 && $isend==1){
                    $starttime = strtotime($startdate);//8:30开始
                    if($startdate !=$enddate){
                        $endtime = strtotime($enddate);//全天+9小时
                    }else{
                        $endtime = 0;
                    } 
                }elseif($isallday==1 && $isend==""){
                    $starttime = strtotime($startdate);//00:00开始
                    $endtime = 0;
                }elseif($isallday=="" && $isend==1){
                    $starttime = strtotime($startdate.' '.$s_time);
                    $endtime = strtotime($enddate.' '.$e_time);
                }else{
                    $starttime = strtotime($startdate.' '.$s_time);
                    $endtime = 0;
                }
                $color = input('post.color')==''?randcolor():input('post.color');
                $isallday = $isallday?1:0;
            
                $cid = Db::name('calendar')->where(array('id'=>$id))->value('cid');
                //判断用户权限,用户所在的公司只能添加自己公司的信息,排除管理员1,2,3,9 mona.lv
                $ucid = session('user.mid');
                if(($ucid != $cid ) and !in_array(session('user.id'),array(1,2,3))){
                echo '没有权限,写入失败！';exit;
                }
                $cal = Db::name('calendar')->where(array('id'=>$id))->save(array('uid'=>session('user.id'),'title'=>$events,'start'=>$starttime,'end'=>$endtime,'allday'=>$isallday,'color'=>$color));
                
                if($cal>0){
                    echo '1';
                }else{
                    echo '出错了！';    
                }
            }elseif($action=="del"){
                $model = M('calendar');
                $id = intval($_POST['id']);
                $cid = $model->where(array('id'=>$id))->getField('cid');
                //判断用户权限,用户所在的公司只能添加自己公司的信息,排除管理员1,2,3,9 mona.lv
                $ucid = session('user.mid');
                if(($ucid != $cid and $ucid != 20) and !in_array(session('user.id'),array(1,2,3,9))){
                echo '没有权限,写入失败！';exit;
                }
                if($id>0){
                    
                $cal = $model->where(array('id'=>$id))->data(array('is_delete'=>1))->save();
                    if($cal>0){
                        echo '1';
                    }else{
                        echo '出错了！';    
                    }
                }else{
                    echo '事件不存在！';
                }
            }elseif($action=="drag"){
                    
                    //拖动修改
                    $model = M('calendar');
                    $id = $_POST['id'];
                    $daydiff = (int)$_POST['daydiff']*24*60*60;
                    $minudiff = (int)$_POST['minudiff']*60;
                    $allday = $_POST['allday'];
                    $row = $model->where(array('id'=>$id))->find();
                    //判断用户权限,用户所在的公司只能添加自己公司的信息,排除管理员1,2,3
                    $ucid = session('user.mid');
                    if(($ucid != $row['cid'] and $ucid != 20) and !in_array(session('user.id'),array(1,2,3))){
                    echo '没有权限,写入失败！';exit;
                    }
                    if($allday=="true"){//如果是全天事件
                        if($row['end']==0){
                            $cal = $model->where(array('id'=>$id))->data(array('uid'=>session('user.id'),'start'=>$row['start']+$daydiff))->save();
                        }else{
                            $cal = $model->where(array('id'=>$id))->data(array('uid'=>session('user.id'),'start'=>$row['start']+$daydiff,'end'=>$row['end']+$daydiff))->save();
                        }

                    }else{ 
                        $difftime = $daydiff + $minudiff;
                        if($row['end']==0){
                            $cal = $model->where(array('id'=>$id))->data(array('uid'=>session('user.id'),'start'=>$row['start']+$difftime))->save();
                        }else{
                           
                            $cal = $model->where(array('id'=>$id))->data(array('uid'=>session('user.id'),'start'=>$row['start']+$difftime,'end'=>$row['end']+$difftime))->save(); 
                        } 
                    } 
                    
                    if($cal>0){
                        echo '1';
                    }else{ 
                        echo '出错了！';     
                    } 
                }elseif($action=="resize"){
                   
                    //日程事件拉长和缩短
                    $model = M('calendar');
                    $id = $_POST['id'];
                    $daydiff = (int)$_POST['daydiff']*24*60*60;
                    $minudiff = (int)$_POST['minudiff']*60;                    
                    $row = $model->where(array('id'=>$id))->find();
                    //判断用户权限,用户所在的公司只能添加自己公司的信息,排除管理员1,2,3
                    $ucid = session('user.mid');
                    if(($ucid != $row['cid'] and $ucid != 20) and !in_array(session('user.id'),array(1,2,3))){
                    echo '没有权限,写入失败！';exit;
                    }
                    $difftime = $daydiff + $minudiff;
                    if($row['end']==0){
                        $cal = $model->where(array('id'=>$id))->data(array('uid'=>session('user.id'),'end'=>$row['start']+$difftime+34200,'color'=>randcolor()))->save();
                    }else{
                        $cal = $model->where(array('id'=>$id))->data(array('uid'=>session('user.id'),'end'=>$row['end']+$difftime,'color'=>randcolor()))->save(); 
                    }
                    if($cal>0){ 
                        echo '1'; 
                    }else{ 
                        echo '出错了！';     
                    } 
                }
        
    }
    //用户查询日历信息
    public function showrili(){
        if (!Request::isAjax()) {
             return redirect('index');
        }

    $model = Db::name('calendar');
    $data = input('get.');
    $type = input('get.type',0);//区分经理级还是协理级
    if($type==1){
        $type = '1';//员工行事历
    }elseif($type==2){
        $type = '2';//经理行事历
    }else{
        $type = '1,2';//员工/经理行程
    }
    if(empty($data['cid'])){
        $where = [['type','in',$type],['start','>',$data['start']],['end','<',$data['end']],['is_delete','=',0]];
    }elseif($data['cid']==1){
        //员工行事历
        $where = [['cid','=',$data['cid']],['type','in',$type],['start','>',$data['start']],['end','<',$data['end']],['is_delete','=',0]];
    }else{
        $where = [['cid','=',$data['cid']],['type','in',$type],['start','>',$data['start']],['end','<',$data['end']],['is_delete','=',0]];
    }  
    $data = $model->where($where)->select()->toArray();//echo  Db::name("users")->getLastSql();exit;
    foreach ($data as $k => $v) {
       if($v['allday']==0){
        $data[$k]['allDay']=false;
       }
    }
    //echo M()->_sql();
    
        return json($data);
    }

    //编辑日历
    public function editrl(){
        $id = input('get.id');        
        if(empty($id)){
                //多选新建里传数据
                $date = input('get.date');
                $enddate = input('get.end');
                if($date==$enddate) $enddate='';
                if(empty($enddate)){                    
                    $data['end_d'] = $date;
                    $data['end_display'] = "style=display:none";
                    $data['end_chk'] = "";
                }else{                    
                    $data['end_d'] = $enddate;
                    $data['end_display'] = "style=''";
                    $data['end_chk'] = "checked";
                }
                
               View::assign('data',$data);
                return View::fetch('editadd');
            
            
        }
        $model = Db::name('calendar');
        $row = $model->where(array('id'=>$id))->find();
        $data['id'] = $row['id'];
        $data['title'] = $row['title'];
        $data['starttime'] = $row['start'];
        $data['start_d'] = date("Y-m-d",$data['starttime']);
        $data['start_h'] = date("H",$data['starttime']);
        $data['start_m'] = date("i",$data['starttime']);
        $data['endtime'] = $row['end'];
        if($data['endtime']==0){
            $data['end_d'] = date("Y-m-d",$data['starttime']);
            $data['end_chk'] = '';
            $data['end_display'] = "style=display:none;";
        }else{
            $data['end_d'] = date("Y-m-d",$data['endtime']);
            $data['end_h'] = date("H",$data['endtime']);
            $data['end_m'] = date("i",$data['endtime']);
            $data['end_chk'] = "checked";
            $data['end_display'] = "style=''";
        }
        
        $data['allday'] = $row['allday'];
        if($data['allday']==1){
            $data['display'] = "style=display:none;";
            $data['allday_chk'] = "checked";
        }else{
            $data['display'] = "style=''";
            $data['allday_chk'] = '';
        }
        $data['color'] = $row['color'];
        View::assign('data',$data);       
        return View::fetch('editrl');
         
       
    }
    //我的行事历
    public function my(){
        
        $count = Db::name('calendar')->where(array('uid'=>session('user.id'),'type'=>2,'is_delete'=>0))->count();
        $page = new Page($count, 16);
        $list = Db::name('calendar')->order('id desc')->where(array('uid'=>session('user.id'),'type'=>2,'is_delete'=>0))->limit($page->firstRow,$page->listRows)->select()->toArray();                
        $page = $page->show();
        View::assign('list', $list);
        View::assign('page', $page);
        return View::fetch();
       
    }
    //用户提交日历
    public function myadd(){
        if (!Request::isPost()) {
                $userinfo = Db::name('users')->field('name,ename,post')->where(array('id'=>session('user.id')))->find();
                View::assign('userinfo',$userinfo);
                return View::fetch();
               
        }
        
        $action = input('get.action');//dump(I('post.'));exit;        
        if($action=='add'){

            $events = stripslashes(trim($_POST['cont']));//事件内容
            $events=strip_tags($events); //过滤HTML标签，并转义特殊字符

            $isallday = $_POST['isallday'];//是否是全天事件
            $isend = $_POST['isend'];//是否有结束时间,一天以内
            $isends = $_POST['isends'];//一天以上的

            $startdate = trim($_POST['startdate']);//开始日期
            $enddate = trim($_POST['enddate']);//结束日期

            $s_time = $_POST['s_hour'].':'.$_POST['s_minute'].':00';//开始时间
            $e_time = $_POST['e_hour'].':'.$_POST['e_minute'].':00';//结束时间

            if($isends==1){
                $starttime = strtotime($startdate);//00:00开始
                if($startdate !=$enddate){
                    $endtime = strtotime($enddate);//全天+9小时
                }else{
                    $endtime = 0;
                }                
                $isallday = 1;//整天
            }elseif($isallday==1 && $isend==""){
                $starttime = strtotime($startdate);//00:00开始
                $endtime = 0;
            }elseif($isallday=="" && $isend==1){
                $starttime = strtotime($startdate.' '.$s_time);
                $endtime = strtotime($enddate.' '.$e_time);
            }else{
                $starttime = strtotime($startdate.' '.$s_time);
                $endtime = 0;
            }
            //echo $startdate.' '.$s_time;exit;
            //$colors = array('#00FFFF','#7FFFD4','#F5F5DC','#90EE90','#E6E6FA','#FFF0F5','#ADFF2F','#EDCBD3','#E0FFFF','#FAFAD2','#90EE90','#FFB6C1','#FFFFE0','#FAF0E6','#FFE4E1','#FFCCCC','#FFFFCC','#FFFF99','#FAC9E8','#CCFF99','#CCFFFF','#FF99FF','#99FF99','#FFCC66','#FFCC66','#CCFFCC');
            //$key = array_rand($colors);
            //$color = $colors[$key];
            //$color = randrgb();
            $color = input('post.color')==''?randcolor():input('post.color');
            $isallday = $isallday?1:0;
            $cid = session('user.mid');//公司ID            
            $type = 1;//行事历           
            //判断用户权限,用户所在的公司只能添加自己公司的信息,排除管理员1,2,3
            // $ucid = session('user.mid');
            // if(($ucid != $cid) and !in_array(session('user.id'),array(1,2,3))){
            //     echo '没有权限,写入失败！';exit;
            // }
            $model = Db::name('calendar');
            $cal = $model->insert(array('cid'=>$cid,'uid'=>session('user.id'),'type'=>$type,'title'=>$events,'start'=>$starttime,'end'=>$endtime,'allday'=>$isallday,'color'=>$color,'time'=>time()));
            //同步到区域栏目
            //$model->data(array('cid'=>$cid,'uid'=>session('user.id'),'type'=>0,'title'=>$events,'start'=>$starttime,'end'=>$endtime,'allday'=>$isallday,'color'=>$color,'time'=>time()))->add();
            
            if($cal>0){
                echo '1';
            }else{
                echo '写入失败！';
            }
        }
    }
    //用户删除
    public function mydel(){            
            if (!Request::isAjax()) {
                    redirect('my');
                    return;
                }
                $model = Db::name('calendar');
                $id = input('get.id');
                $uid = session('user.id');
                //$info = $model->where(array('id'=>$id,'uid'=>$uid))->find();
                $cal = $model->where(array('id'=>$id,'uid'=>$uid))->save(array('is_delete'=>1));                
                if($cal>0){
                //删除同步到区域的信息
                //$model->where(array('type'=>0,'uid'=>$uid,'title'=>$info['title'],'start'=>$info['start']))->data(array('is_delete'=>1))->save();
                        echo '1';
                }else{
                        echo '出错了！';  
                }
               
    }

}