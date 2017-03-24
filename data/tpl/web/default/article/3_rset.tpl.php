<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li  <?php  if($op == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('rset', array('op' => 'display'))?>">管理</a></li>
	<li  <?php  if($op == 'post') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('rset', array('op' => 'post'))?>">添加</a></li>
</ul>
<?php  if($op=='display') { ?>
<div class="main">
	<div class="clear" style="margin-bottom:10px;"></div>
	<div class="panel panel-default">
		<div class="panel-heading">打赏档位列表</div>
		<div class="panel-body table-responsive">
			<table class="table table-hover">
				<tbirthdayad class="navbar-inner">
					<tr>
						<th style="width:5px;">序号</th>
						<th style="width:30px;">标题</th>
						<th style="width:20px;">打赏积分</th>
						<th style="width:10px;">排序</th>
						<th style="width:30px;">是否显示</th>
						<th style="width:40px;">创建时间</th>
						<th style="width:90px;">操作</th>
					</tr>
				</tbirthdayad>
				<tbody>
				<?php  if(is_array($list)) { foreach($list as $k => $item) { ?>
				<tr id="list">
					<td><?php  echo $k+1?></td>
					<td><?php  echo $item['title'];?></td>
					<td><?php  echo $item['score'];?></td>
					<td><?php  echo $item['sort'];?></td>
					<td><?php  if($item['status'] == 0) { ?>显示<?php  } else { ?>不显示<?php  } ?></td>
					<td><?php  echo date('Y-m-d H:i',$item['time'])?></td>
					<td>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('rset',array('op'=>'post', 'id'=>$item['id']))?>"><i class="fa fa-edit"></i> 详情编辑</a>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('rset',array('op'=>'delete', 'id'=>$item['id']))?>" onclick="return confirm('您确定要删除吗?');return false;"><i class="fa fa-times"></i>删除</a>
					</td>
				</tr>
				<?php  } } ?>
				</tbody>
				<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
			</table>
		</div>
	</div>
	<?php  echo $pager;?>
</div>
<?php  } else if($op=='post') { ?>
<div class="main">
	<form action="<?php  echo $this->createWebUrl('rset',array('op'=>'post'))?>" class="form-horizontal" method="post" enctype="multipart/form-data" >
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">标题</label>
					<div class="col-md-2 col-lg-2">
						<input type="text" name="title" id="title"  class="form-control" value="<?php  echo $info['title'];?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">打赏积分</label>
					<div class="col-md-2 col-lg-1">
						<input type="text" name="score" id="score"  class="form-control" value="<?php  echo $info['score'];?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">是否显示</label>
					<div class="col-md-2 col-lg-1">
						<select class="form-control" style="margin-right:15px;" name="status">
							<option value="0" <?php  if($info['status']=='0') { ?>selected<?php  } ?>>显示</option>
							<option value="1" <?php  if($info['status']=='1') { ?>selected<?php  } ?>>不显示</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">排序</label>
					<div class="col-md-2 col-lg-1">
						<input type="text" name="sort" id="sort"  class="form-control" value="<?php  echo $info['sort'];?>">
					</div>【数字越大越靠后，不填即为0】
				</div>
				<div class='form-group text-center'>
					<input class="btn btn-primary" type="submit" name="sub">
					<input type="hidden" name="id" value="<?php  echo $id;?>" />
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
				</div>
			</div>
		</div>
	</form>
</div>
<?php  } ?>
<script>
    function CheckedRev(){
        var arr = $(':checkbox');
        for(var i=0;i<arr.length;i++){
            arr[i].checked = ! arr[i].checked;
        }
    }

    $("#btn_search").click(function(){
        var name = $("input[name='name']").val();
        var onlycode = $("input[name='onlycode']").val();
        var tel = $("input[name='tel']").val();
        var str = '';
        if(name){
            str += "&name="+name;
        }
        if(onlycode){
            str += "&code="+onlycode;
        }
        if(tel){
            str += "&tel="+tel;
        }
        $("#form_search").attr("action", "<?php  echo $this->createWebUrl('rset',array('op'=>'display'))?>"+str);
    });

    function drop_confirm(msg, url) {
        if (confirm(msg)) {
            window.location = url;
        }
    }

    function deleteall() {
        var check = $("input[name='check']:checked");
        console.log(check);
        if (check.length < 1) {
            alert('请选择要删除的记录!');
            return false;
        }
        if( confirm("确认要删除选择的记录?")){
            var id = new Array();
            check.each(function(i){
                id[i] = $(this).val();
            });

            $.post("<?php  echo $this->createWebUrl('rset',array('op'=>'deleteall'))?>", {idArr:id},function(data){
                if (data.message.error == 0)
                {
                    alert(data.message.msg);
                    location.reload();
                } else {
                    alert(data.message.msg);
                }
            },'json');
        }
    };
</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>


























