<?php

namespace app\admin\controller;

use app\admin\controller\ComController;
use think\facade\View;
use think\facade\Db;
use fast\Page;
class Update extends ComController
{


   //开发日志
    public function devlog($p = 1)
    {


        $model = Db::name('devlog');
        $count = $model->count();        
        $page = new Page($count,8);
        $data = $model->order('id desc')->limit($page->firstRow , $page->listRows)->select();
        $show = $page->show();
        View::assign('page', $show);
        View::assign('firstRow',$page->firstRow);
        View::assign('list',$data); 
        View::assign('nav', array('setting', 'devlog'));//导航
        return View::fetch();
    }
    //添加开发日志
    public function addlog(){
        $data = input('post.');
        if(empty($data['v']) || empty($data['log'])){
            $this->error('数据不能为空！');
        }
        $model = Db::name('devlog')->insert(array('v'=>$data['v'],'y'=>date('Y',time()),'t'=>time(),'log'=>htmlspecialchars_decode($data['log'])));
        if($model>0){
            $this->success('添加成功！');
        }

    }
    public function update()
    {

        $this->assign('nav', array('setting', 'update', ''));//导航
        $this->display();
    }

    public function updating()
    {

        set_time_limit(0);
        $file = isset($_GET['file']) ? $_GET['file'] : false;
        if ($file) {

            //升级包下载
            $url = C('UPDATE_URL') . $file;
            $file = new HttpDownload(); # 实例化类
            $file->OpenUrl($url); # 远程文件地址
            $result = $file->SaveToBin("update.zip"); # 保存路径及文件名
            $file->Close();
            if (!$result) {
                addlog('升级包下载失败。');
                die(json_encode(array('message' => '抱歉，升级文件下载失败，请稍后再试或前往官方下载升级包手动升级！')));
            }
            $archive = new Zip();
            $result = $archive->unzip('update.zip', './');
            if ($result == -1) {
                die(json_encode(array('message' => '抱歉，升级包解压失败，请手动解压并导入update.sql升级数据库！')));
            }
            //数据库升级
            $sql = true;
            if (file_exists('update.sql')) {
                $sql = file_get_contents('update.sql');
                $prefix = C('DB_PREFIX');
                $sql = str_replace('qw_', $prefix, $sql);
                if (!M()->execute($sql)) {
                    $sql = false;
                }
            }
            if (!$sql) {
                addlog('数据库升级失败。');
                die(json_encode(array('message' => '数据库升级失败！请手动导入update.sql文件执行数据库升级！')));
            }

            //删除升级包
            @unlink('update.zip');

            if (file_exists('update.sql')) {
                @unlink('update.sql');
            }
            if (file_exists('update.zip')) {
                addlog('升级成功。');
                die(json_encode(array('message' => '恭喜，请手动删除update.zip和update.sql（若存在）！')));
            }
            addlog('升级成功。');
            die(json_encode(array('message' => '恭喜，升级成功！')));
        } else {
            die('2');//参数错误
        }
    }

    
}
