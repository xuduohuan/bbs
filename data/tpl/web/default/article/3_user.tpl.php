<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li <?php  if($op == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('user', array('op' => 'display'))?>">管理</a></li>
	<li <?php  if($op == 'label') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('user', array('op' => 'label'))?>">标签管理</a></li>
</ul>
<?php  if($op=='display') { ?>
<div class="panel panel-info">
	<div class="panel-heading">筛选</div>
	<div class="panel-body">
		<form id="form_search" action="" method="post" class="form-horizontal" role="form">
			<div class="form-group">
				<label class="col-md-2 col-lg-1 control-label">姓名</label>
				<div class="col-md-2 col-lg-2">
					<input class="form-control" name="name" type="text" value="<?php  echo $name;?>" placeholder="姓名">
				</div>
				<label class="col-md-2 col-lg-1 control-label">标签</label>
				<div class="col-md-2 col-lg-2">
					<select class="form-control" style="margin-right:15px;" name="v">
						<option value="" <?php  if(empty($v)) { ?>selected<?php  } ?>></option>
						<option value="普通用户组" <?php  if($v == '普通用户组') { ?>selected<?php  } ?>>普通用户组</option>
						<?php  if(is_array($label)) { foreach($label as $row) { ?>
						<option value="<?php  echo $row['label'];?>" <?php  if($v ==$row['label']) { ?>selected<?php  } ?>><?php  echo $row['label'];?></option>
						<?php  } } ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-2 col-lg-offset-1 col-md-2 col-lg-1">
					<button id="btn_search" type="submit" class="btn btn-success"><i class="fa fa-search"></i> 搜索</button>
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
				</div>
			</div>
		</form>
	</div>
</div>
<div class="main">
	<div class="clear" style="margin-bottom:10px;"></div>
	<div class="panel panel-default">
		<div class="panel-heading">用户列表【用户数量：<?php  echo $user_num;?>】</div>
		<div class="panel-body table-responsive">
			<table class="table table-hover">
				<tbirthdayad class="navbar-inner">
					<tr>
						<th style="width:5px;">序号</th>
						<th style="width:30px;">昵称</th>
						<th style="width:30px;">微信头像</th>
						<th style="width:20px;">标签</th>
						<th style="width:40px;">上次登录时间</th>
						<th style="width:40px;">积分</th>
						<th style="width:40px;">权限</th>
						<th style="width:40px;">访问状态</th>
						<th style="width:90px;">操作</th>
					</tr>
				</tbirthdayad>
				<tbody>
				<?php  if(is_array($list)) { foreach($list as $k => $item) { ?>
				<tr>
					<td><?php  echo $k+1?></td>
					<td><?php  echo $item['nickname'];?></td>
					<td><img src="<?php  echo $_W['attachurl'];?><?php  echo $item['avatar'];?>" width="60px"></td>
					<td><?php  echo $item['v'];?></td>
					<td><?php  echo date('Y-m-d H:i',$item['lasttime'])?></td>
					<td><?php  echo $item['score'];?></td>
					<td><?php  echo $item['lid'];?></td>
					<td><?php  if($item['black'] == 0) { ?>正常<?php  } else { ?>禁止访问<?php  } ?></td>
					<td>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('user',array('op'=>'behavior', 'id'=>$item['id']))?>"><i class="fa fa-user"></i> 用户行为</a>
						<?php  if($item['black'] == 0) { ?>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('user',array('op'=>'delete', 'id'=>$item['id']))?>" onclick="return confirm('您确定要禁止吗?');return false;"><i class="fa fa-times"></i>禁止访问</a>
						<?php  } else { ?>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('user',array('op'=>'quitdelete', 'id'=>$item['id']))?>" onclick="return confirm('您确定要取消禁止吗?');return false;"><i class="fa fa-times"></i>取消禁止</a>
						<?php  } ?>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('user',array('op'=>'addv', 'id'=>$item['id']))?>"><i class="fa fa-star"></i> <?php  if(empty($item['v'])) { ?>加标签<?php  } else { ?>更新标签<?php  } ?></a>
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
	<form action="<?php  echo $this->createWebUrl('pics',array('op'=>'post'))?>" class="form-horizontal" method="post" enctype="multipart/form-data" >
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">添加链接</label>
					<div class="col-md-2 col-lg-5">
						<input type="text" name="url" id="url"  class="form-control" value="<?php  echo $info['url'];?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-1 col-lg-1 control-label">照片</label>
					<div class="upload_img col-xs-8" style="line-height:0.5;">
						<?php  echo tpl_form_field_image('pics',$info['pics']);?>
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
				<div class='form-group text-center'>
					<input class="btn btn-primary" type="submit" name="sub">
					<input type="hidden" name="id" value="<?php  echo $id;?>" />
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
				</div>
			</div>
		</div>
	</form>
</div>
<?php  } else if($op=='addv') { ?>
<div class="main">
	<form action="<?php  echo $this->createWebUrl('user',array('op'=>'addv'))?>" class="form-horizontal" method="post" enctype="multipart/form-data" >
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">用户标签</label>
					<div class="col-md-2 col-lg-3">
						<select class="form-control" style="margin-right:15px;" name="v">
							<?php  if(is_array($label)) { foreach($label as $row) { ?>
							<option value="<?php  echo $row['label'];?>" <?php  if($info['v']==$row['label']) { ?>selected<?php  } ?>><?php  echo $row['label'];?></option>
							<?php  } } ?>
						</select>
					</div>
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
<?php  } else if($op =='label') { ?>
<?php  if($oop =='display') { ?>
<div class="main">
	<div class="clear" style="margin-bottom:10px;"></div>
	<div class="panel panel-default">
		<div class="panel-heading">用户标签
			<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('user',array('op'=>'label', 'oop'=>'post'))?>">添加</a>
		</div>
		<div class="panel-body table-responsive">
			<table class="table table-hover">
				<tbirthdayad class="navbar-inner">
					<tr>
						<th style="width:5px;">序号</th>
						<th style="width:30px;">标签名称</th>
						<th style="width:20px;">排序</th>
						<th style="width:90px;">操作</th>
					</tr>
				</tbirthdayad>
				<tbody>
				<?php  if(is_array($label)) { foreach($label as $k => $item) { ?>
				<tr>
					<td><?php  echo $k+1?></td>
					<td><?php  echo $item['label'];?></td>
					<td><?php  echo $item['sort'];?></td>
					<td>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('user',array('op'=>'label', 'oop'=>'post', 'id'=>$item['id']))?>"><i class="fa fa-edit"></i> 编辑</a>
						<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('user',array('op'=>'label', 'oop'=>'del', 'id'=>$item['id']))?>" onclick="return confirm('您确定要删除吗?');return false;"><i class="fa fa-times"></i>删除</a>
					</td>
				</tr>
				<?php  } } ?>
				</tbody>
				<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
			</table>
		</div>
	</div>
</div>
<?php  } else if($oop =='post') { ?>
<div class="main">
	<form action="<?php  echo $this->createWebUrl('user',array('op'=>'label','oop'=>'post'))?>" class="form-horizontal" method="post" enctype="multipart/form-data" >
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">标签名称</label>
					<div class="col-md-2 col-lg-3">
						<input type="text" name="label" id="label"  class="form-control" value="<?php  echo $history['label'];?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">排序</label>
					<div class="col-md-2 col-lg-2">
						<input type="text" name="sort" id="sort"  class="form-control" value="<?php  echo $history['sort'];?>">
					</div>【数字越大越靠后】
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
<?php  } else if($op =='behavior') { ?>
<div class="main">
	<div class="clear" style="margin-bottom:10px;"></div>
	<div class="panel panel-default">
		<div class="panel-heading">用户行为记录</div>
		<div class="panel-body table-responsive">
			<table class="table table-hover">
				<tbirthdayad class="navbar-inner">
					<tr>
						<th style="width:5px;">序号</th>
						<th style="width:5px;">行为类别</th>
						<th style="width:10px;">行为对象</th>
						<th style="width:60px;">说明1</th>
						<th style="width:60px;">说明2</th>
						<th style="width:40px;">操作时间</th>
						<!--<th style="width:60px;">操作</th>-->
					</tr>
				</tbirthdayad>
				<tbody>
				<?php  if(is_array($arr_limit)) { foreach($arr_limit as $k => $item) { ?>
				<tr>
					<td><?php  echo $k+1?></td>
					<td <?php  if($item['tip'] == '藏') { ?>style="color: #67b168"
						<?php  } else if($item['tip'] == '赞') { ?>style="color: #D2B48C"}
						<?php  } else if($item['tip'] == '评') { ?>style="color: #9400D3"}
						<?php  } else { ?>
						style="color: #1E90FF"
						<?php  } ?>>
						<?php  echo $item['tip'];?>
					</td>
					<td><?php  if($item['type'] == 1) { ?>文章<?php  } else { ?>帖子<?php  } ?></td>
					<td><?php  if($item['type'] == 1) { ?><a href="<?php  echo $this->createWebUrl('article',array('op'=>'post', 'id'=>$item['id']))?>">【文章标题】<?php  echo $item['title'];?></a><?php  } else { ?><a href="<?php  echo $this->createWebUrl('post',array('op'=>'post', 'id'=>$item['id']))?>">【帖子内容】<?php  echo $item['title'];?></a><?php  } ?></td>
					<td><?php  if($item['tip'] == '评') { ?><?php  if($item['type'] == 1) { ?>【文章评论】<?php  echo $item['content'];?><?php  } else { ?>【帖子评论】<?php  echo $item['content'];?><?php  } ?><?php  } ?></td>
					<td><?php  echo date('Y-m-d H:i',$item['time'])?></td>
					<!--<td>-->
						<!--<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('user',array('op'=>'behavior', 'id'=>$item['id']))?>"><i class="fa fa-user"></i> 用户行为</a>-->
						<?php  if($item['black'] == 0) { ?>
						<!--<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('user',array('op'=>'delete', 'id'=>$item['id']))?>" onclick="return confirm('您确定要禁止吗?');return false;"><i class="fa fa-times"></i>禁止访问</a>-->
						<?php  } else { ?>
						<!--<a class="btn btn-default btn-sm" href="<?php  echo $this->createWebUrl('user',array('op'=>'quitdelete', 'id'=>$item['id']))?>" onclick="return confirm('您确定要取消禁止吗?');return false;"><i class="fa fa-times"></i>取消禁止</a>-->
						<?php  } ?>
					<!--</td>-->
				</tr>
				<?php  } } ?>
				</tbody>
				<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
			</table>
		</div>
	</div>
	<?php  echo $pager;?>
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
        var v = $("input[name='v']").val();

        var str = '';
        if(name){
            str += "&name="+name;
        }
        if(v){
            str += "&v="+v;
        }
        $("#form_search").attr("action", "<?php  echo $this->createWebUrl('user',array('op'=>'display'))?>"+str);
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

            $.post("<?php  echo $this->createWebUrl('pics',array('op'=>'deleteall'))?>", {idArr:id},function(data){
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


























