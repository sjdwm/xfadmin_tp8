<?php /*a:1:{s:42:"D:\www\tp\app\Admin\view\member\ulist.html";i:1699328099;}*/ ?>
  <table class="table table-hover" id="myuser">
      <thead>
      
        <tr>
          <th><input  type="checkbox"  id="quan" quan="0" style="width: 16px;height: 16px;vertical-align: -3px;" > 用户ID</th>
          <th>姓名</th>
          <th>用户名</th>          
          <th>在职状态</th> 
          <th>启用状态</th>        
          <th>用户组</th>
          <th>公司</th>
          <th>部门</th>
          <th>职务</th>
           <th>邮箱</th>
          <th>登录时间</th>
          <th>注册时间</th>
          
          
          <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;操 作</th>
        </tr>
      </thead>
      <tbody >
        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <tr>
          <td><input  type="checkbox" class="user" id="us<?php echo htmlentities($vo['id']); ?>" xuan="0" check="<?php echo htmlentities($vo['id']); ?>" onclick="dan(<?php echo htmlentities($vo['id']); ?>)" style="width: 16px;height: 16px;vertical-align: -3px;" > <?php echo htmlentities($vo['id']); ?></td>
          <td><?php echo htmlentities($vo['name']); ?></td>
          <td><?php echo htmlentities($vo['username']); ?></td>
          <td><div id="bt<?php echo htmlentities($vo['id']); ?>" class="<?php echo $vo['status']=='0' ? 'glyphicon glyphicon-ok' : 'glyphicon glyphicon-remove'; ?> ds" <?php echo $vo['status']=='0' ? 'style="color : #089641;"':'style="color:red;"'; ?> onClick="redoit(<?php echo htmlentities($vo['id']); ?>,'bt<?php echo htmlentities($vo['id']); ?>')">
          </div></td>
          <td><div id="btt<?php echo htmlentities($vo['id']); ?>" class="<?php echo $vo['stop']=='0' ? 'glyphicon glyphicon-ok' : 'glyphicon glyphicon-remove'; ?> ds" <?php echo $vo['stop']=='0' ? 'style="color : #089641;"':'style="color:red;"'; ?> onClick="redoitt(<?php echo htmlentities($vo['id']); ?>,'btt<?php echo htmlentities($vo['id']); ?>')">
          </div></td>
          <td><?php echo user_group($vo['id']); ?></td>
          <td><?php echo user_company($vo['mid']); ?></td>
          <td><?php echo user_company($vo['gid']); ?></td>
          <td><?php echo htmlentities($vo['post']); ?></td>
          <td><?php echo htmlentities($vo['email']); ?></td>
          
          <td><?php echo date("Y-m-d H:i:s",strtotime("+0 hours",$vo['login_time'])); ?></td>
          <td><?php echo date("Y-m-d H:i:s",strtotime("+0 hours",$vo['reg_time'])); ?></td>
          <td>
            <a href="<?php echo url('edit',array('uid'=>$vo['id'])); ?>" class="bbtn bbtn-primary">修改</a>
            <!-- <span data-toggle="modal" data-target="#myModalw" class="btn btn-primary" onClick="edituser(<?php echo htmlentities($vo['id']); ?>)">修改</span> -->
            <!-- <span data-toggle="modal" data-target="#modalshow" class="bbtn bbtn-success" onClick="showuser(<?php echo htmlentities($vo['id']); ?>)">查看</span> -->
            <a href="<?php echo url('show', array('uid'=>$vo['id'])); ?>" class="bbtn bbtn-success">查看</a>
            <!-- <a href="<?php echo url('pashow', array('id'=>$vo['u_id'])); ?>" class="btn btn-info">密码</a> -->
            <span data-toggle="modal" data-target="#myModalp" class="bbtn bbtn-info" onClick="edituserpwd(<?php echo htmlentities($vo['id']); ?>,'<?php echo htmlentities($vo['username']); ?>')">密码</span>
            <!-- <a href="<?php echo url('delete', array('id'=>$vo['u_id'])); ?>" class="btn btn-danger">删除</a> -->
            <!-- <span  class="btn btn-danger" onClick="dell(<?php echo htmlentities($vo['id']); ?>,this)">删除</span> -->
          </td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        <tr>
                <td colspan=13>
                   <div class=>
                      <ul class="pagination pagination-xs pagination-custom">
                        <?php echo $show; ?>
                      </ul>
                    </div>
                </td>
              </tr>
      </tbody>
    </table>
<!-- 密码修改Modal -->
  <div class="modal fade" id="myModalp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-login" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">修改密码</h4>
        </div>
        <div class="modal-body">
          <form action="<?php echo url('pass'); ?>" method='post' class="form-horizontal" id='form-pass'>
            <div class="form-group">
          <label for="inputEmail3" class="col-sm-3 control-label">用户账号</label>
          <div class="col-sm-7">
            <input type="text" class="form-control vip" id="input11" name="" value="" disabled>
          </div>
        </div>
        <div class="form-group">
          <label for="inputPassword3" class="col-sm-3 control-label">用户密码</label>
          <div class="col-sm-7">
            <input type="text" class="form-control" id="input22" name="password" value="" required>
          </div>
        </div>
          <div class="form-group">
          <label for="inputPassword3" class="col-sm-3 control-label">确认密码</label>
          <div class="col-sm-7">
            <input type="text" class="form-control" id="input33" name="repassword" value="" required>
          </div>
        </div>
      
          <input type="hidden" name="uid" id="uid" value="">
          <input type="hidden" name="username" id="username" value="">
        
          </form>
        </div>
        <div class="modal-footer">       
          <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
          <button type="button" class="btn btn-primary" form="form-pass" onClick="editpwd()">修改</button>
        </div>
      </div>
    </div>
  </div>
<script>
 $(".pagination a").click(function(){
 cur_page = $(this).data('p');
 lists(cur_page);
 });
 $('#count').text(<?php echo htmlentities($count); ?>);
 //全选框删除会员
        $("#quan").click(function(){
          if($("#quan").attr('quan') == 1){
            $("#quan").attr('quan',0);
            $(".user").attr('xuan',0);
            $("input").each(function(i){
              this.checked = 0;
            });
            
          }else{
            $("#quan").attr('quan',1);
            $(".user").attr('xuan',1)
            $("input").each(function(i){
              this.checked = 1;
            });
            

          }
        })
        //单选删除会员
        function dan(a){
          if($('#us'+a).attr('xuan') == 1){
            $('#us'+a).attr('xuan',0);
            $('#us'+a).each(function(i){
              this.checked = 0;
            });
            $("#quan").each(function(i){
              this.checked = 0;
            });
            }else{
            $('#us'+a).attr('xuan',1);
            $('#us'+a).each(function(i){
              this.checked = 1;
            });
            
            $("#quan").each(function(i){
              this.checked = 0;
            });
            
            
          }
        }
        //执行单选删除会员
        function del(){
          //console.log($(".user").length);
          $(".user").each(function(i){
              var uid=$(this).attr('check');
              var xuan=$(this).attr('xuan');
              if(uid >= 1 && xuan == 1){
              $.ajax({
                    url:"<?php echo url('del'); ?>",
                    type:'get',
                    data:('uid='+uid),
                    success:function(value){
                      //alert(value);
                                  
                    window.location.reload(); 

                    }
                   });
              }
            });
          
        }
        //停用账户
        function redoitt(b,c){
            //var bt=window.document.getElementById(c);
            restt = $('#'+c).hasClass('glyphicon glyphicon-remove ds');
            if(restt !== true){
              //bt.innerHTML="禁用";
              $('#'+c).attr("class","glyphicon glyphicon-remove ds");
              $('#'+c).attr("style","color:red");
              restt = 0;
              sstop('0',b);                    
            }else{
              //bt.innerHTML="正常";
              $('#'+c).attr('class','glyphicon glyphicon-ok ds');
              $('#'+c).attr("style","color:#089641;");
               restt = 1;  
              sstop('1',b);                  
            }
          }
          //设置是否停用账户
          function sstop(a,b){
            
             $.ajax({
              url:'<?php echo url('userstop'); ?>',
              type:'get',
              data:"v="+a+'&id='+b,
              success:function(value){
                //alert(value);
              }
             });
          } 
          //在职状态
        function redoit(b,c){
            //var bt=window.document.getElementById(c);
            restt = $('#'+c).hasClass('glyphicon glyphicon-remove ds');
            if(restt !== true){
              //bt.innerHTML="禁用";
              $('#'+c).attr("class","glyphicon glyphicon-remove ds");
              $('#'+c).attr("style","color:red");
              restt = 0;
              stop('0',b);                    
            }else{
              //bt.innerHTML="正常";
              $('#'+c).attr('class','glyphicon glyphicon-ok ds');
              $('#'+c).attr("style","color:#089641;");
               restt = 1;  
              stop('1',b);                  
            }
          }
          //设置在职状态
          function stop(a,b){
            
             $.ajax({
              url:'<?php echo url('userstatus'); ?>',
              type:'get',
              data:"v="+a+'&id='+b,
              success:function(value){
                //alert(value);
              }
             });
          } 
        //ajax修改用户密码
        function edituserpwd(a,b){
          //p是获取当前分页信息传到控制器
          $('#input11').val(b);  
          $('#uid').val(a);  
          $('#username').val(b);  
  
        }
        function editpwd(){
        $.ajax({
          url:"/Admin/member/pass",
          type:'post',
          data:$('#form-pass').serialize(),
          success:function(data){
            if(data.code==0){
              layer.msg(data.msg, {icon: 0});
            }else if(data.code==1){
              layer.msg(data.msg, {icon: 0});
            }else if(data.code==2){
              layer.msg(data.msg, {icon: 1});
            }
          
          }
        });
      } 
</script>