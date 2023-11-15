<?php /*a:1:{s:41:"D:\www\tp\app\admin\view\Public\jump.html";i:1699940269;}*/ ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?php echo htmlentities(config('config.sitename')); ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <link rel="shortcut icon" href="/static/favicon.ico" type="image/x-icon" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <link href="/static/Bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="/static/Jump/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    
    <style>#imgVerify{width: 120px;margin: 0 auto; text-align: center;display: block;}	</style>
  </head>
  <body class="login-page">
    <div class="login-box ma_t_cm">
   
<?php switch ($code) {case 1:?> 
      <!--处理成功-->
      <div class="login-box-body">
        <h4 class="login-box-msg ver_cm"><span class="glyphicon glyphicon-ok ver_cm"></span> <?php echo(strip_tags($msg));?></h4>
          <a href="javascript:void(0);">页面自动 <a id="href" href="<?php echo($url);?>">等待跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b></a><br /><br />  
          <a href="https://www.hotxf.com" target="_parent">网站前台</a>
          <a href="https://www.hotxf.com" target="_parent">管理后台</a>

      </div>      
<?php break;case 0:?>   
      <!--处理失败-->
       <div class="login-box-body">
        <h4 class="login-box-msg ver_cm"><span class="glyphicon glyphicon-remove ver_cm"></span> <?php echo(strip_tags($msg));?></h4>
          <a href="javascript:void(0);">页面自动 <a id="href" href="<?php echo($url);?>">等待跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b></a><br /><br />
          <a href="https://www.hotxf.com" target="_parent">网站前台</a>
          <a href="https://www.hotxf.com" target="_parent">管理后台</a>
      </div>
<?php break;} ?>    
	    <div class="margin text-center">
	        <div class="copyright">
	            2023-<?php echo date('Y'); ?> &copy; <a href="http://www.hotxf.com">小风博客--技术支持 v1.0.0</a>
	            <br/>
	        </div>
	    </div>
    </div><!-- /.login-box -->

<script type="text/javascript">

(function(){
            var wait = document.getElementById('wait'),
                href = document.getElementById('href').href;
            var interval = setInterval(function(){
                var time = --wait.innerHTML;
                if(time <= 0) {
                    location.href = href;
                    clearInterval(interval);
                };
            }, 1000);
        })();

</script>    
  </body>
</html>