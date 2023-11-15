<?php /*a:1:{s:48:"D:\www\tp\app\Admin\view\logs\user_log_list.html";i:1699588682;}*/ ?>
<table class="table table-hover">
                 <tbody>
<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vol): $mod = ($i % 2 );++$i;?>
                <tr>
                    <td style="text-align: center;width: 80px;"><?php echo htmlentities($vol['id']); ?></td>
                    <td style="text-align: center;width:200px;"><?php echo htmlentities($vol['name']); ?></td>
                    <td style="text-align: center;width:350px;"><?php echo htmlentities($vol['desc']); ?></td>
              		<td style="text-align: center;width:250px;"><?php echo htmlentities($vol['ip']); ?></td>
              		<td><?php echo date("Y-m-d H:i:s",$vol['time']); ?></td>
              	</tr>
<?php endforeach; endif; else: echo "" ;endif; ?>
              <tr>
                <td colspan=7>
                   <div class=>
                      <ul class="pagination pagination-xs pagination-custom">
                        <?php echo $show; ?>
                      </ul>
                    </div>
                </td>
              </tr>
</tbody>
</table>
<script>
 $(".pagination a").click(function(){
 cur_page = $(this).data('p');
 lists(cur_page);
 });
</script>