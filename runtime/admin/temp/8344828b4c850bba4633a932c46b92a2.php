<?php /*a:8:{s:42:"D:\www\tp\app\Admin\view\article\form.html";i:1700024173;s:41:"D:\www\tp\app\Admin\view\Public\head.html";i:1699858880;s:43:"D:\www\tp\app\Admin\view\Public\header.html";i:1699955670;s:44:"D:\www\tp\app\Admin\view\Public\sidebar.html";i:1698138668;s:48:"D:\www\tp\app\Admin\view\Public\breadcrumbs.html";i:1698233895;s:40:"D:\www\tp\app\Admin\view\Public\set.html";i:1699254043;s:43:"D:\www\tp\app\Admin\view\Public\footer.html";i:1699942786;s:45:"D:\www\tp\app\Admin\view\Public\footerjs.html";i:1700020946;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta charset="utf-8"/>
    <title><?php echo htmlentities($current['title']); ?>-<?php echo htmlentities($web_config['sitename']); ?></title>

    <meta name="keywords" content="<?php echo cache('keywords'); ?>"/>
    <meta name="description" content="<?php echo cache('description'); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <link rel="shortcut icon" href="/static/favicon.ico" type="image/x-icon" />

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="/static/Bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/static/xfadmin/css/font-awesome.css"/>
    <link rel="stylesheet" href="/static/xfadmin/css/jquery-ui.css"/>
    <link rel="stylesheet" href="/static/xfadmin/css/public.css"/>
    <!-- page specific plugin styles -->

    <!-- text fonts -->
    <link rel="stylesheet" href="/static/xfadmin/css/ace-fonts.css"/>

    <!-- ace styles -->
    <link rel="stylesheet" href="/static/xfadmin/css/ace.css" class="ace-main-stylesheet" id="main-ace-style"/>
    <link href="/static/static/layui/css/layui.css" rel="stylesheet">
    <script src="/static/static/layui/layui.js"></script>
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="/static/xfadmin/css/ace-part2.css" class="ace-main-stylesheet"/>
    <![endif]-->

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="/static/xfadmin/css/ace-ie.css"/>

    <![endif]-->

    <!-- inline styles related to this page -->

    <!-- ace settings handler -->
    <script src="/static/xfadmin/js/ace-extra.js"></script>
    <script src="/static/js/jquery-1.11.3.min.js"></script>

    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

    <!--[if lte IE 8]>
    <script src="/static/xfadmin/js/html5shiv.js"></script>
    <script src="/static/xfadmin/js/respond.js"></script>
    <![endif]-->
</head>
<body class="no-skin">
    <!-- #section:basics/navbar.layout -->
    <div id="navbar" class="navbar navbar-default">
        <script type="text/javascript">
            try {
                ace.settings.check('navbar', 'fixed')
            } catch (e) {
            }
        </script>

        <div class="navbar-container" id="navbar-container">
            <!-- #section:basics/sidebar.mobile.toggle -->
            <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
                <span class="sr-only">Toggle sidebar</span>

                <span class="icon-bar"></span>

                <span class="icon-bar"></span>

                <span class="icon-bar"></span>
            </button>

            <!-- /section:basics/sidebar.mobile.toggle -->
            <div class="navbar-header pull-left">
                <!-- #section:basics/navbar.layout.brand -->
                <a href="<?php echo url('index/index'); ?>" class="navbar-brand">
                    <small>
                        <i class="fa fa-home"></i>
                        <?php echo htmlentities(config('config.sitename')); ?>
                    </small>
                </a>

                <!-- /section:basics/navbar.layout.brand -->

                <!-- #section:basics/navbar.toggle -->

                <!-- /section:basics/navbar.toggle -->
            </div>

            <!-- #section:basics/navbar.dropdown -->
            <div class="navbar-buttons navbar-header pull-right" role="navigation">
                <ul class="nav ace-nav">
                    <!-- #section:basics/navbar.user_menu -->
                    <li class="red">
                        <a href="<?php echo url('Login/clear'); ?>" title="清除缓存" target="_self">
                            <i class="ace-icon fa fa-trash-o"></i>
                        </a>
                    </li>
                    <li class="red">
                        <a href="/" title="前台首页" target="_blank">
                            <i class="ace-icon fa fa-home"></i>
                        </a>
                    </li>
                    <li class="light-blue">
                        <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                            <img class="nav-user-photo" src="<?php if(($user['head_img'] == '')): ?>/static/xfadmin/img/user.png
                            <?php else: ?><?php echo htmlentities($user['head_img']); ?><?php endif; ?>" alt="<?php echo htmlentities($user['user']); ?>" /}
                            <span class="user-info">
                                <small>欢迎光临，</small>
                                <?php echo htmlentities($user['user']); ?>
                            </span>

                            <i class="ace-icon fa fa-caret-down"></i>
                        </a>

                        <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                            <li>
                                <a href="<?php echo url('Setting/Setting'); ?>">
                                    <i class="ace-icon fa fa-cog"></i>
                                    设置
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo url('Member/show',array('uid'=>app('request')->session('user.id'))); ?>">
                                    <i class="ace-icon fa fa-user"></i>
                                    个人资料
                                </a>
                            </li>

                            <li class="divider"></li>

                            <li>
                                <a href="<?php echo url('Login/logout'); ?>">
                                    <i class="ace-icon fa fa-power-off"></i>
                                    退出
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- /section:basics/navbar.user_menu -->
                </ul>
            </div>

            <!-- /section:basics/navbar.dropdown -->
        </div><!-- /.navbar-container -->
    </div>

    <!-- /section:basics/navbar.layout -->
<div class="main-container" id="main-container">
    <script type="text/javascript">
        try {
            ace.settings.check('main-container', 'fixed')
        } catch (e) {
        }
    </script>

        <!-- #section:basics/sidebar -->
    <div id="sidebar" class="sidebar responsive">
        <script type="text/javascript">
            try {
                ace.settings.check('sidebar', 'fixed')
            } catch (e) {
            }
        </script>

        <div class="sidebar-shortcuts" id="sidebar-shortcuts">
            <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                <button class="btn btn-success">
                    <i class="ace-icon fa fa-signal"></i>
                </button>

                <button class="btn btn-info">
                    <i class="ace-icon fa fa-pencil"></i>
                </button>

                <!-- #section:basics/sidebar.layout.shortcuts -->
                <button class="btn btn-warning">
                    <i class="ace-icon fa fa-users"></i>
                </button>

                <button class="btn btn-danger">
                    <i class="ace-icon fa fa-cogs"></i>
                </button>

                <!-- /section:basics/sidebar.layout.shortcuts -->
            </div>

            <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                <span class="btn btn-success"></span>

                <span class="btn btn-info"></span>

                <span class="btn btn-warning"></span>

                <span class="btn btn-danger"></span>
            </div>
        </div><!-- /.sidebar-shortcuts -->

        <ul class="nav nav-list">
            <?php if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                <li
                <?php if(($v['id'] == $current['id']) OR ($v['id'] == $current['pid']) OR ($v['id'] == $current['ppid'])): ?>
                    class="active
                    <?php if($current['pid'] != '0'): ?>open<?php endif; ?>
                    "
                <?php endif; ?>
                >
                <a href="<?php if(empty($v['name']) || (($v['name'] instanceof \think\Collection || $v['name'] instanceof \think\Paginator ) && $v['name']->isEmpty())): ?>#
                <?php else: ?>
                <?php echo url($v['name']); ?><?php endif; ?>"
                <?php if(!(empty($v['children']) || (($v['children'] instanceof \think\Collection || $v['children'] instanceof \think\Paginator ) && $v['children']->isEmpty()))): ?>class="dropdown-toggle"<?php endif; ?>
                >
                <i class="<?php echo htmlentities($v['icon']); ?>"></i>
                <span class="menu-text">
                                    <?php echo htmlentities($v['title']); ?>
                                </span>
                <?php if(!(empty($v['children']) || (($v['children'] instanceof \think\Collection || $v['children'] instanceof \think\Paginator ) && $v['children']->isEmpty()))): ?>
                    <b class="arrow fa fa-angle-down"></b>
                <?php endif; ?>
                </a>

                <b class="arrow"></b>
                <?php if(!(empty($v['children']) || (($v['children'] instanceof \think\Collection || $v['children'] instanceof \think\Paginator ) && $v['children']->isEmpty()))): ?>
                    <ul class="submenu">
                        <?php if(is_array($v['children']) || $v['children'] instanceof \think\Collection || $v['children'] instanceof \think\Paginator): $i = 0; $__LIST__ = $v['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?>

                            <li
                            <?php if(($vv['id'] == $current['id']) OR ($vv['id'] == $current['pid'])): ?>class="active
                                <?php if($current['ppid'] != '0'): ?>open<?php endif; ?>
                                "
                            <?php endif; ?>
                            >
                            <a href="<?php if(empty($vv['children']) || (($vv['children'] instanceof \think\Collection || $vv['children'] instanceof \think\Paginator ) && $vv['children']->isEmpty())): ?><?php echo url($vv['name']); else: ?>
                            #<?php endif; ?>"
                            <?php if(!(empty($vv['children']) || (($vv['children'] instanceof \think\Collection || $vv['children'] instanceof \think\Paginator ) && $vv['children']->isEmpty()))): ?>class="dropdown-toggle"<?php endif; ?>
                            >
                            <i class="<?php echo htmlentities($vv['icon']); ?>"></i>
                            <?php echo htmlentities($vv['title']); if(!(empty($vv['children']) || (($vv['children'] instanceof \think\Collection || $vv['children'] instanceof \think\Paginator ) && $vv['children']->isEmpty()))): ?><b class="arrow fa fa-angle-down"></b><?php endif; ?>
                            </a>

                            <b class="arrow"></b>
                            <?php if(!(empty($vv['children']) || (($vv['children'] instanceof \think\Collection || $vv['children'] instanceof \think\Paginator ) && $vv['children']->isEmpty()))): ?>
                                <ul class="submenu">
                                    <?php if(is_array($vv['children']) || $vv['children'] instanceof \think\Collection || $vv['children'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vv['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vvv): $mod = ($i % 2 );++$i;?>
                                        <li
                                        <?php if($vvv['id'] == $current['id']): ?>class="active"<?php endif; ?>
                                        >
                                        <a href="<?php echo url($vvv['name']); ?>">
                                            <i class="<?php echo htmlentities($vvv['icon']); ?>"></i>
                                            <?php echo htmlentities($vvv['title']); ?>
                                        </a>
                                        <b class="arrow"></b>
                                        </li>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </ul>
                            <?php endif; ?>
                            </li>

                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                <?php endif; ?>
                </li>
            <?php endforeach; endif; else: echo "" ;endif; ?>

        </ul><!-- /.nav-list -->

        <!-- #section:basics/sidebar.layout.minimize -->
        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left"
               data-icon2="ace-icon fa fa-angle-double-right"></i>
        </div>

        <!-- /section:basics/sidebar.layout.minimize -->
        <script type="text/javascript">
            try {
                ace.settings.check('sidebar', 'collapsed')
            } catch (e) {
            }
        </script>
    </div>

    <!-- /section:basics/sidebar -->
    <div class="main-content">
        <div class="main-content-inner">
            <!-- #section:basics/content.breadcrumbs -->
                <div class="breadcrumbs" id="breadcrumbs">
        <script type="text/javascript">
            try {
                ace.settings.check('breadcrumbs', 'fixed')
            } catch (e) {
            }
        </script>

        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="<?php echo url('index/index'); ?>"><?php if($_SESSION['user']['lang'] == 1): ?>Home<?php else: ?>首页<?php endif; ?></a>
            </li>
            <?php if(isset($current['ptitle'])): ?>
                <li>
                    <a href="<?php echo url('index'); ?>"><?php echo htmlentities($current['ptitle']); ?></a>
                </li>
            <?php endif; ?>
            <li class="active"><?php echo htmlentities($current['title']); ?></li>
        </ul><!-- /.breadcrumb -->
    </div>

            <!-- /section:basics/content.breadcrumbs -->
            <div class="page-content">
                    <!-- #section:settings.box -->
    <?php if($current['tips'] != ''): ?>
        <div class="alert alert-block alert-success">
            <button type="button" class="close" data-dismiss="alert">
                <i class="ace-icon fa fa-times"></i>
            </button>
            <!--i class="ace-icon fa fa-check green"></i-->
            <?php echo htmlentities($current['tips']); ?>
        </div>
    <?php endif; ?>
    <div class="ace-settings-container" id="ace-settings-container">
        <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
            <i class="ace-icon fa fa-cog bigger-130"></i>
        </div>

        <div class="ace-settings-box clearfix" id="ace-settings-box">
            <div class="pull-left width-50">
                <!-- #section:settings.skins -->
                <div class="ace-settings-item">
                    <div class="pull-left">
                        <select id="skin-colorpicker" class="hide">
                            <option data-skin="no-skin" value="#438EB9">#438EB9</option>
                            <option data-skin="skin-1" value="#222A2D">#222A2D</option>
                            <option data-skin="skin-2" value="#C6487E">#C6487E</option>
                            <option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
                        </select>
                    </div>
                    <span>&nbsp; 选择皮肤</span>
                </div>

                <!-- /section:settings.skins -->

                <!-- #section:settings.navbar -->
                <div class="ace-settings-item">
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar"/>
                    <label class="lbl" for="ace-settings-navbar"> 固定导航条</label>
                </div>

                <!-- /section:settings.navbar -->

                <!-- #section:settings.sidebar -->
                <div class="ace-settings-item">
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar"/>
                    <label class="lbl" for="ace-settings-sidebar"> 固定侧边栏</label>
                </div>

                <!-- /section:settings.sidebar -->

                <!-- #section:settings.breadcrumbs -->
                <div class="ace-settings-item">
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs"/>
                    <label class="lbl" for="ace-settings-breadcrumbs"> 固定面包屑</label>
                </div>

                <!-- /section:settings.breadcrumbs -->

                <!-- #section:settings.rtl -->
                <div class="ace-settings-item">
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl"/>
                    <label class="lbl" for="ace-settings-rtl"> 切换至左边</label>
                </div>

                <!-- /section:settings.rtl -->

                <!-- #section:settings.container -->
                <div class="ace-settings-item">
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container"/>
                    <label class="lbl" for="ace-settings-add-container">
                        切换宽窄度
                    </label>
                </div>

                <!-- /section:settings.container -->
            </div><!-- /.pull-left -->

            <div class="pull-left width-50">
                <!-- #section:basics/sidebar.options -->
                <div class="ace-settings-item">
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover"/>
                    <label class="lbl" for="ace-settings-hover"> 子菜单收起</label>
                </div>

                <div class="ace-settings-item">
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact"/>
                    <label class="lbl" for="ace-settings-compact"> 侧边栏紧凑</label>
                </div>

                <div class="ace-settings-item">
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight"/>
                    <label class="lbl" for="ace-settings-highlight"> 当前位置</label>
                </div>

                <!-- /section:basics/sidebar.options -->
            </div><!-- /.pull-left -->
        </div><!-- /.ace-settings-box -->
    </div><!-- /.ace-settings-container -->

                <!-- /section:settings.box -->
                <div class="row">
                    <div class="col-xs-12">
                        <form class="form-horizontal" id="form" method="post" action="<?php echo url('update'); ?>">

                            <!-- PAGE CONTENT BEGINS -->

                            <input type="hidden" name="aid" value="<?php echo htmlentities($article['aid']); ?>" id="aid"/>
                            <div class="form-group">
                                <label class="col-sm-1 control-label no-padding-right" for="form-field-0">
                                    文章分类 </label>
                                <div class="col-sm-9">
                                    <select id="cid" name="cid" class="col-xs-10 col-sm-5">
                                        <option value="0">--分类--</option>
                                        <?php echo $category; ?>
                                    </select>

                                    <span class="help-inline col-xs-12 col-sm-7">
												<span class="middle">选择所属分类。</span>
											</span>
                                </div>
                            </div>

                            <div class="space-4"></div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1">
                                    标题 </label>
                                <div class="col-sm-9">
                                    <input type="text" name="title" id="title" placeholder="中文标题"
                                           class="col-xs-10 col-sm-5" value="<?php echo htmlentities($article['title']); ?>">
                                    <span class="help-inline col-xs-12 col-sm-7">
												<span class="middle">文章标题</span>
											</span>
                                </div>
                            </div>
                               <div class="space-4"></div>
                           <!--  <div class="form-group">
                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1">
                                    英文标题 </label>
                                <div class="col-sm-9">
                                    <input type="text" name="title_en" id="title_en" placeholder="英文标题"
                                           class="col-xs-10 col-sm-5" value="<?php echo htmlentities($article['title_en']); ?>">
                                    <span class="help-inline col-xs-12 col-sm-7">
                                                <span class="middle">英文标题</span>
                                            </span>
                                </div>
                            </div> -->
                           <!--  <div class="form-group">
                                <label class="col-sm-1 control-label no-padding-right" for="form-field-1">
                                    作者 </label>
                                <div class="col-sm-9">
                                    <input type="text" name="author" id="author" placeholder=""
                                           class="col-xs-10 col-sm-5" value="<?php echo htmlentities($article['author']); ?>">
                                    <span class="help-inline col-xs-12 col-sm-7">
												<span class="middle">文章作者</span>
											</span>
                                </div>
                            </div> -->

                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="col-sm-1 control-label no-padding-right" for="form-field-2">
                                    关键词 </label>
                                <div class="col-sm-9">
                                    <input type="text" name="keywords" id="keywords" placeholder="多个关键词用顿号分隔"
                                           class="col-xs-10 col-sm-5" value="<?php echo htmlentities($article['keywords']); ?>">
                                    <span class="help-inline col-xs-12 col-sm-7">
												<span class="middle">文章关键词。</span>
											</span>
                                </div>
                            </div>

                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="col-sm-1 control-label no-padding-right" for="form-field-3">
                                    文章摘要 </label>
                                <div class="col-sm-9">
                                        <textarea name="description" id="description" placeholder="可以不填，如不填；则截取文章内容前300字为描述"
                                                  class="col-xs-10 col-sm-5"
                                                  rows="5"><?php echo htmlentities($article['description']); ?></textarea>
                                    <span class="help-inline col-xs-12 col-sm-7">
												<span class="middle">文章摘要、描述。</span>
											</span>
                                </div>
                            </div>
                            <div class="space-4"></div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label no-padding-right" for="form-field-4">
                                    缩略图 </label>
                                <div class="col-sm-9">
                                    <div class="col-xs-10 col-sm-5">
                                         <button type="button" class="layui-btn" id="test11">
                                            <i class="layui-icon">&#xe67c;</i>上传图片
                                        </button>
                                        <img id='img' src="<?php echo htmlentities($article['thumbnail']); ?>" alt="" style="width: 100px;height: 100px;">
                                        

                                    </div>

                                    <span class="help-inline col-xs-12 col-sm-7">
												<span class="middle">仅支持jpg、gif、png、bmp、jpeg，且小于2MB。</span>
											</span>
                                </div>
                                
                            </div>
                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="col-sm-1 control-label no-padding-right" for="form-field-3">
                                    属性 </label>
                                <div class="col-sm-9" style="padding-top: 6px;">
                                <label for="in3">
                                        
                                        <input class="ace ace-checkbox-2 children" id="in3" type="checkbox" name="is_show" value="1" <?php if($article['is_show'] == 1): ?>checked="checked"<?php endif; ?> ><span class="lbl"> 是否显示</span></label>
                                        &emsp;
                                <label for="in2">
                                        
                                        <input class="ace ace-checkbox-2 children" id="in2" type="checkbox" name="is_top" value="1" <?php if($article['is_top'] == 1): ?>checked="checked"<?php endif; ?>><span class="lbl"> 是否置顶</span></label>
                                        &emsp;
                                <label for="in1">
                                        
                                        <input class="ace ace-checkbox-2 children" id="in1" type="checkbox" name="is_s" value="1" <?php if($article['is_s'] == 1): ?>checked="checked"<?php endif; ?>><span class="lbl"> 是否首页</span></label>
                                        &emsp;

                                                                            
                                </div>
                            </div>
                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="col-sm-1 control-label no-padding-right" for="form-field-2">
                                    文章内容 </label>
                                <div class="col-sm-9">
                                <!-- 引入ueditor编辑器开始 -->
                               <!--  <script id="container" name="content" type="text/plain"><?php echo $article['content']; ?></script>
                                <script src="/static/static/ueditor1_4_3/ueditor.config.js"></script>
                                <script src="/static/static/ueditor1_4_3/ueditor.all.js"></script>
                                <script>
                                    var ue = UE.getEditor('container');
                                </script> -->
                                <!-- 进入ueditor编辑器结束 -->
                                    <script id="container" name="content" type="text/plain"><?php echo $article['content'];?></script>
<script src="/static/static/ueditor1_4_3/ueditor.config.js"></script>
<script src="/static/static/ueditor1_4_3/ueditor.all.js"></script>
<script>
    var ue = UE.getEditor('container');
</script>
                                       
                                   
                                </div>
                            </div>
                            <div class="space-4"></div>


                            <div class="col-md-offset-2 col-md-9">
                                <button class="btn btn-info submit" type="button">
                                    <i class="icon-ok bigger-110"></i>
                                    提交
                                </button>

                                &nbsp; &nbsp; &nbsp;
                                <button class="btn" type="reset">
                                    <i class="icon-undo bigger-110"></i>
                                    重置
                                </button>
                            </div>

                            <!-- PAGE CONTENT ENDS -->


                        </form>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->
    <div class="footer">
    <div class="footer-inner">
        <!-- #section:basics/footer -->
        <div class="footer-content">
                        <span class="bigger-120">
                            <small>Copyright &copy;2023 - <?php echo date('Y'); ?> <a href="https://hotxf.com" target="_blank">小风博客</a> All Rights Reserved.by <a href="https://hotxf.com" target="_blank">jd.she</a></small>
                        </span>
        </div>
        <!-- /section:basics/footer -->
    </div>
</div>
<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
    <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
</a>
</div><!-- /.main-container -->
<!-- basic scripts -->

<!--[if !IE]> -->
<script type="text/javascript">
    window.jQuery || document.write("<script src='/static/xfadmin/js/jquery.js'>" + "<" + "/script>");
</script>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='/static/xfadmin/js/jquery1x.js'>" + "<" + "/script>");
</script>
<![endif]-->
<script type="text/javascript">
    if ('ontouchstart' in document.documentElement) document.write("<script src='/static/xfadmin/js/jquery.mobile.custom.js'>" + "<" + "/script>");
</script>
<!-- <script src="/static/xfadmin/js/bootstrap.js"></script> -->
<script src="/static/Bootstrap/js/bootstrap.min.js"></script>
<script src="/static/js/xiaofeng.js"></script>
<!-- page specific plugin scripts -->
<!-- <script charset="utf-8" src="/static/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="/static/kindeditor/lang/zh_CN.js"></script> -->

<!-- ace scripts -->
<script src="/static/xfadmin/js/ace/elements.scroller.js"></script>
<script src="/static/xfadmin/js/ace/elements.colorpicker.js"></script>
<script src="/static/xfadmin/js/ace/elements.fileinput.js"></script>
<script src="/static/xfadmin/js/ace/elements.typeahead.js"></script>
<script src="/static/xfadmin/js/ace/elements.wysiwyg.js"></script>
<script src="/static/xfadmin/js/ace/elements.spinner.js"></script>
<script src="/static/xfadmin/js/ace/elements.treeview.js"></script>
<script src="/static/xfadmin/js/ace/elements.wizard.js"></script>
<script src="/static/xfadmin/js/ace/elements.aside.js"></script>
<script src="/static/xfadmin/js/ace/ace.js"></script>
<script src="/static/xfadmin/js/ace/ace.ajax-content.js"></script>
<script src="/static/xfadmin/js/ace/ace.touch-drag.js"></script>
<script src="/static/xfadmin/js/ace/ace.sidebar.js"></script>
<script src="/static/xfadmin/js/ace/ace.sidebar-scroll-1.js"></script>
<script src="/static/xfadmin/js/ace/ace.submenu-hover.js"></script>
<script src="/static/xfadmin/js/ace/ace.widget-box.js"></script>
<script src="/static/xfadmin/js/ace/ace.settings.js"></script>
<script src="/static/xfadmin/js/ace/ace.settings-rtl.js"></script>
<script src="/static/xfadmin/js/ace/ace.settings-skin.js"></script>
<script src="/static/xfadmin/js/ace/ace.widget-on-reload.js"></script>
<script src="/static/xfadmin/js/ace/ace.searchbox-autocomplete.js"></script>
<script src="/static/xfadmin/js/jquery-ui.js"></script>
<script src="/static/xfadmin/js/bootbox.js"></script>
<script src="/static/static/layer-2.4/layer.js"></script>

<!-- inline scripts related to this page -->
<script type="text/javascript">
//上传图片  
up_img("<?php echo url('Article/upimg'); ?>",'#test11');

    $(function () {

        $(".submit").click(function () {
            var sid = $("#sid").val();
            var title = $("#title").val();
            var content = $("#content").val();
            if (sid == 0) {
                bootbox.dialog({
                    title: '友情提示：',
                    message: "文章分类必须选择。",
                    buttons: {
                        "success": {
                            "label": "确定",
                            "className": "btn-danger"
                        }
                    }
                });
                return;
            }
            if (title == '') {
                bootbox.dialog({
                    title: '友情提示：',
                    message: "文章标题必须填写。",
                    buttons: {
                        "success": {
                            "label": "确定",
                            "className": "btn-danger"
                        }
                    }
                });
                return;
            }
            if (content == '') {
                bootbox.dialog({
                    title: '友情提示：',
                    message: "文章内容需要填写。",
                    buttons: {
                        "success": {
                            "label": "确定",
                            "className": "btn-danger"
                        }
                    }
                });
                return;
                return;
            }
            $("#form").submit();
        });
    });
</script>

</body>
</html>
