<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
	<li  <?php  if($op == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('index', array('op' => 'display'))?>">管理</a></li>
	<!--<li  <?php  if($op == 'post') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('index', array('op' => 'post'))?>">添加</a></li>-->
</ul>
<?php  if($op=='display') { ?>
<div class="panel panel-info">
	<div class="panel-heading">帖子组【取某话题下浏览量最大的4个置于首页，可叠加】</div>
	<div class="panel-body">
		<form action="<?php  echo $this->createWebUrl('index',array('op'=>'display','ty'=>'post'))?>" class="form-horizontal" method="post">
			<div class="form-group">
				<label class="col-md-2 col-lg-1 control-label">话题</label>
				<div class="col-md-2 col-lg-2">
					<select class="form-control" style="margin-right:15px;" name="topic">
						<?php  if(is_array($topic)) { foreach($topic as $row) { ?>
						<option value="<?php  echo $row['id'];?>"><?php  echo $row['title'];?></option>
						<?php  } } ?>
					</select>
				</div>
				<div class="col-md-offset-2 col-lg-offset-1 col-md-2 col-lg-1">
					<input class="btn btn-primary" type="submit" name="sup">
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
				</div>
			</div>
		</form>
		<div class="panel panel-default">
			<div class="panel-heading">当前首页帖子组展示信息</div>
			<div class="panel-body table-responsive">
				<table class="table table-hover">
					<tbirthdayad class="navbar-inner">
						<?php  if($plist) { ?>
						<tr>
							<th style="width:5px;">序号</th>
							<th style="width:20px;">所属话题</th>
							<th style="width:10px;">发帖用户</th>
							<th style="width:50px;">帖子内容</th>
							<th style="width:10px;">浏览量</th>
							<th style="width:20px;">是否显示</th>
							<th style="width:30px;">发帖时间</th>
						</tr>
						<?php  } ?>
					</tbirthdayad>
					<tbody>
					<?php  if($plist) { ?>
						<?php  if(is_array($plist)) { foreach($plist as $k => $item) { ?>
						<tr>
							<td><?php  echo $k+1?></td>
							<td><?php  echo $item['tt'];?></td>
							<td><?php  echo $item['nickname'];?></td>
							<td><?php  echo $item['content'];?></td>
							<td><?php  echo $item['click'];?></td>
							<td><?php  if($item['status'] =='0') { ?>显示<?php  } else { ?>不显示<?php  } ?></td>
							<td><?php  echo date('Y-m-d H:i',$item['time'])?></td>
						</tr>
						<?php  } } ?>
					<?php  } else { ?>
						暂时无首页数据，请设置
					<?php  } ?>
					</tbody>
					<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
				</table>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-success">
	<div class="panel-heading">文章组【取某分类下浏览量最大的4篇文章置于首页，可叠加】</div>
	<div class="panel-body">
		<form action="<?php  echo $this->createWebUrl('index',array('op'=>'display','ty'=>'post'))?>" class="form-horizontal" method="post">
			<div class="form-group">
				<label class="col-md-2 col-lg-1 control-label">话题</label>
				<div class="col-md-2 col-lg-2">
					<select class="form-control" style="margin-right:15px;" name="topic">
						<?php  if(is_array($topic)) { foreach($topic as $row) { ?>
						<option value="<?php  echo $row['id'];?>"><?php  echo $row['title'];?></option>
						<?php  } } ?>
					</select>
				</div>
				<div class="col-md-offset-2 col-lg-offset-1 col-md-2 col-lg-1">
					<input class="btn btn-primary" type="submit" name="sua">
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
				</div>
			</div>
		</form>
		<div class="panel panel-default">
			<div class="panel-heading">当前首页文章组展示信息</div>
			<div class="panel-body table-responsive">
				<table class="table table-hover">
					<tbirthdayad class="navbar-inner">
						<?php  if($plist) { ?>
						<tr>
							<th style="width:5px;">序号</th>
							<th style="width:20px;">所属话题</th>
							<th style="width:10px;">发帖用户</th>
							<th style="width:50px;">帖子内容</th>
							<th style="width:10px;">浏览量</th>
							<th style="width:20px;">是否显示</th>
							<th style="width:30px;">发帖时间</th>
						</tr>
						<?php  } ?>
					</tbirthdayad>
					<tbody>
					<?php  if($plist) { ?>
					<?php  if(is_array($plist)) { foreach($plist as $k => $item) { ?>
					<tr>
						<td><?php  echo $k+1?></td>
						<td><?php  echo $item['tt'];?></td>
						<td><?php  echo $item['nickname'];?></td>
						<td><?php  echo $item['content'];?></td>
						<td><?php  echo $item['click'];?></td>
						<td><?php  if($item['status'] =='0') { ?>显示<?php  } else { ?>不显示<?php  } ?></td>
						<td><?php  echo date('Y-m-d H:i',$item['time'])?></td>
					</tr>
					<?php  } } ?>
					<?php  } else { ?>
					暂时无首页数据，请设置
					<?php  } ?>
					</tbody>
					<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
				</table>
			</div>
		</div>
	</div>
</div>
<?php  } else if($op=='post') { ?>
<div class="main">
	<form action="<?php  echo $this->createWebUrl('index',array('op'=>'post'))?>" class="form-horizontal" method="post" enctype="multipart/form-data" >
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label class="col-md-2 col-lg-1 control-label">公告</label>
					<div class="col-md-2 col-lg-5">
						<input type="text" name="notice" id="notice"  class="form-control" value="<?php  echo $info['index'];?>">
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
        $("#form_search").attr("action", "<?php  echo $this->createWebUrl('index',array('op'=>'display'))?>"+str);
    });

    function drop_confirm(msg, url) {
        if (confirm(msg)) {
            window.location = url;
        }
    }
</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>


























