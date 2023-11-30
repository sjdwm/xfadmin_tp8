<?php

namespace app\admin\controller;

use app\admin\controller\ComController;
use think\facade\View;
use think\facade\Db;
use fast\Page;
use fast\Tree;
use think\facade\Request;
use think\facade\Config;
class Article extends ComController
{


   public function add(){
        $category = Db::name('category')->field('id,pid,name')->order('o asc')->where(array('show'=>0))->select()->toArray();
        $tree = new Tree($category);
        $str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
        $category = $tree->get_tree(0, $str, 0);
        View::assign('category', $category);//导航
        return View::fetch('form');
    }

    public function index($sid = 0, $p = 1){
        $cid = input('get.cid');
        $keyword = input('get.keyword','','htmlentities');
        $order = input('get.order','DESC');
        $where = 'a.is_delete=0 ';
        if ($cid) {
            $sids_array = category_get_sons($cid);
            $sids = implode(',',$sids_array);
            $where .= "and a.cid in ($sids) ";
        }
        if ($keyword) {
            $where .= "and a.title like '%{$keyword}%' ";
        }
        //默认按照时间降序
        $orderby = "time desc";
        if ($order == "asc") {

            $orderby = "time asc";
        }//dump($where);exit;
        //获取栏目分类
        $category = Db::name('category')->field('id,pid,name')->order('o asc')->select()->toArray();
        $tree = new Tree($category);
        $str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
        $category = $tree->get_tree(0, $str, $cid);
        View::assign('category', $category);//分类
        $count = Db::name('article')->alias('a')->where($where)->join('category c', 'c.id = a.cid')->count();
        $page = new Page($count,12);        
        $list = Db::name('article')->alias('a')->field("a.*,c.name,c.pid")->where($where)->order($orderby)->join('category c', 'c.id = a.cid')->limit($page->firstRow,$page->listRows)->select();//dump(Db::getLastSql());exit;
        foreach ($list as $key => $value) {
            $list[$key]['s_name'] = Db::name('category')->where(array('id'=>$value['pid']))->value('name').'-';
        }
        //dump($list);exit;
        $page = $page->show();
        View::assign('list', $list);
        View::assign('page', $page);
        return View::fetch();
    }
    //删除文章
    public function del(){

        $aids = isset($_REQUEST['aids']) ? $_REQUEST['aids'] : false;
        if ($aids) {
            if (is_array($aids)) {
                //$aids = implode(',', $aids);
                $map['aid'] = $aids;
            } else {
                $map = 'aid=' . $aids;
            }
            if (Db::name('article')->where($map)->save(array('is_delete'=>1))) {
                //addlog('删除文章，AID：' . $aids);
                $this->success('恭喜，文章删除成功！');
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }

    }
    //编辑文章
    public function edit(){

        $aid = input('get.aid');
        $article = Db::name('article')->where(array('aid'=>$aid))->find();
        if ($article) {

            $category = Db::name('category')->field('id,pid,name')->order('o asc')->where(array('show'=>0))->select()->toArray();
            $tree = new Tree($category);
            $str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
            $category = $tree->get_tree(0, $str, $article['cid']);
            View::assign('category', $category);//导航

            View::assign('article', $article);
        } else {
            $this->error('参数错误！');
        }
        return View::fetch('form');
    }
    //修改文章
    public function update(){

        $aid = input('aid',0);
        $data['cid'] = input('post.cid',0);
        $data['title'] = input('post.title',false);
        $data['is_show'] = input('post.is_show',0);
        $data['is_top'] = input('post.is_top',0);
        $data['is_s'] = input('post.is_s',0);
        $data['keywords'] = input('post.keywords', '', 'strip_tags');
        $data['description'] = input('post.description', '', 'strip_tags');
        $data['content'] = input('post.content',false);
        $data['thumbnail'] = input('post.thumbnail', '', 'strip_tags');
        
        if (!$data['cid'] or !$data['title'] or !$data['content']) {
            $this->error('警告！文章分类、文章标题及文章内容为必填项目。');
        }
        if ($aid) {
            // 反转义为下文的 preg_replace使用
            $data['content']=htmlspecialchars_decode($data['content']);
            // 修改图片默认的title和alt
            $data['content']=preg_replace('/title=\"(?<=").*?(?=")\"/','title="hotxf.com"',$data['content']);
            $data['content']=preg_replace('/alt=\"(?<=").*?(?=")\"/','alt="hotxf.com"  class="zoom" onclick="zoom(this, this.getAttribute(&#39;zoomfile&#39;)||this.src, 0, 0, 1)"',$data['content']);
            //$data['content']=htmlspecialchars($data['content']);
            // 将绝对路径转换为相对路径
            $data['content']=preg_replace('/src=\"^\/.*\/Upload\/image\/ueditor$/','src="/Upload/image/ueditor',$data['content']);
            Db::name('article')->where('aid',$aid)->save($data);
            session('tem_article_img',null);//销毁上传的图片地址
            //addlog('编辑文章，AID：' . $aid);
            $this->success('恭喜！文章编辑成功！','index');
        } else {
            $data['time'] = time();
            $aid=Db::name('Article')->insert($data);
            if ($aid) {
                //addlog('新增文章，AID：' . $aid);
                session('tem_article_img',null);//销毁上传的图片地址
                $this->success('恭喜！文章新增成功！','index');
            } else {
                $this->error('抱歉，未知错误！');
            }

        }
    }
    //设置文章是否显示
    public function astop(){
        if (!Request::isAjax()) {
             return redirect('index');
        }
            $vo = input('get.v');
            $name = input('get.name');
            $id = input('get.id',0);
            //$this->ajaxReturn($vo);exit;
        if($vo == 0){
            //要修改is_show值,0正常,1不显示
            //$data['is_show'] = 0;
            Db::name("article")->where(array('aid'=>$id))->save(array($name=>0));
        }elseif($vo == 1){
            //$data['is_show'] = 1;
            Db::name("article")->where(array('aid'=>$id))->save(array($name=>1)); // 根据条件更新记录        
        }
            
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
       
             $savename = \think\facade\Filesystem::disk('public')->putFile( '', $files['file']);
            
            // 上传成功
            $src = config('filesystem.disks.public.url').'/'.str_replace('\\','/',$savename);
            $tem = session('tem_article_img');//避免上传多于的文件
            if(!empty($tem)){
                @unlink('.'.$tem);//dump($tem);
            }            
            session('tem_article_img',$src);
            $res = array('code'=>0,'msg'=>'上传成功','data'=>array('src'=>$src));
            echo json_encode($res);
        } catch (\think\exception\ValidateException $e) {
           //echo $e->getMessage();上传错误提示错误信息
            $res = array('code'=>1,'msg'=>$e->getMessage(),'data'=>array('src'=>''));
            echo json_encode($res);
        }
       
        

    }

    
}
