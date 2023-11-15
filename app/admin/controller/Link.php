<?php

namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\facade\View;
use think\facade\Db;
class Link extends BaseController
{


   //友情链接
    public function index()
    {

        $list = Db::name('links')->order('o asc')->select();
        View::assign('list', $list);
        return View::fetch();
    }

    //新增链接
    public function add()
    {

        return View::fetch('form');
    }

    //新增或修改链接
    public function edit()
    {

        $id = input('id',false,'intval');
        $link = Db::name('links')->where('id',$id)->find();
        View::assign('link', $link);
        return View::fetch('form');
    }

    //删除链接
    public function del()
    {

        $ids = input('ids',false);
        if ($ids) {
            if (is_array($ids)) {
                $ids = implode(',', $ids);
                $map['id'] = array('in', $ids);
            } else {
                $map = 'id=' . $ids;
            }
            if (Db::name('links')->where($map)->delete()) {
                $this->success('恭喜，删除成功！');
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }
    }

    //保存链接
    public function update()
    {
        $id = input('id',false,'intval');
        $data['title'] = input('post.title', '', 'strip_tags');
        if (!$data['title']) {
            $this->error('请填写标题！');
        }
        $data['url'] = input('post.url', '', 'strip_tags');
        $data['o'] = input('post.o', '', 'strip_tags');
        $pic = input('post.logo', '', 'strip_tags');
        if ($pic <> '') {
            $data['logo'] = $pic;
        }
        if ($id) {
            Db::name('links')->where('id',$id)->save($data);
        } else {
            Db::name('links')->insert($data);
        }
        session('tem_link_img',null);//销毁上传的图片地址
        $this->success('恭喜，操作成功！', url('index'));
    }

    //文章缩略图上传
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
       
             $savename = \think\facade\Filesystem::disk('link_img')->putFile( '', $files['file']);
            
            // 上传成功
            $src = config('filesystem.disks.link_img.url').'/'.str_replace('\\','/',$savename);
            $tem = session('tem_link_img');//避免上传多于的文件
            if(!empty($tem)){
                @unlink('.'.$tem);
            }            
            session('tem_link_img',$src);
            $res = array('code'=>0,'msg'=>'上传成功','data'=>array('src'=>$src));
            echo json_encode($res);
        } catch (\think\exception\ValidateException $e) {
           //echo $e->getMessage();上传错误提示错误信息
            $res = array('code'=>1,'msg'=>$e->getMessage(),'data'=>array('src'=>''));
            echo json_encode($res);
        }
       
        

    }

    
}
